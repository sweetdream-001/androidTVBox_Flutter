import 'dart:async';
import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:video_player/video_player.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:http/http.dart' as http;
import 'package:marquee/marquee.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'TV Box App',
      theme: ThemeData.dark(),
      home: MediaScreen(),
    );
  }
}

class MediaScreen extends StatefulWidget {
  const MediaScreen({super.key});

  @override
  _MediaScreenState createState() => _MediaScreenState();
}

class _MediaScreenState extends State<MediaScreen> {
  List<MediaItem> playlist = [];
  int currentIndex = 0;
  VideoPlayerController? _controller;
  bool _isVideoPlaying = false;

  // This is the list of messages(weather) to be displayed
  List<String> messages = [];

  @override
  void initState() {
    super.initState();
    fetchPlaylist();
    fetchMessages();
  }

  // Fetch messages from the server
  Future<void> fetchMessages() async {
    final response = await http.get(Uri.parse('http://localhost/main/api/weather-ads'));
    if (response.statusCode == 200) {
      final List<dynamic> data = jsonDecode(response.body);
      setState(() {
        messages = data.cast<String>();
      });
    } else {
      print('Failed to load weather messages');
    }
  }

  Future<void> fetchPlaylist() async {
    final response = await http.get(Uri.parse('http://localhost/main/api/media-files'));
    if (response.statusCode == 200) {
      final List<dynamic> data = jsonDecode(response.body);
      setState(() {
        playlist = data.map((item) => MediaItem.fromJson(item)).toList();
        if (playlist.isNotEmpty) {
          playMedia(playlist[0]);
        }
      });
    } else {
      throw Exception('Failed to load playlist');
    }
  }

  Future<void> playMedia(MediaItem item) async {
    if (item.type == 'video') {
      _controller = VideoPlayerController.networkUrl(Uri.parse(item.url));
      await _controller!.initialize();
      setState(() {
        _isVideoPlaying = true;
      });
      await _controller!.play();
      _controller!.addListener(() {
        if (_controller!.value.position == _controller!.value.duration) {
          playNext();
        }
      });
    } else {
      setState(() {
        _isVideoPlaying = false;
      });
      Timer(const Duration(seconds: 5), () {
        playNext();
      });
    }
  }

  void playNext() {
    if (playlist.isEmpty) return;
    currentIndex = (currentIndex + 1) % playlist.length;
    _controller?.dispose();
    playMedia(playlist[currentIndex]);
  }

  @override
  void dispose() {
    super.dispose();
    _controller?.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('TV Box App'),
      ),
      body: Stack(
        children: [
          Center(
            child: _isVideoPlaying
                ? (_controller != null && _controller!.value.isInitialized)
                    ? AspectRatio(
                        aspectRatio: _controller!.value.aspectRatio,
                        child: VideoPlayer(_controller!),
                      )
                    : const CircularProgressIndicator()
                : (playlist.isNotEmpty)
                    ? CachedNetworkImage(
                      imageUrl: playlist[currentIndex].url,
                      placeholder: (context, url) => const CircularProgressIndicator(),
                      errorWidget: (context, url, error) => const Icon(Icons.error),
                    )

                    : const CircularProgressIndicator(),
          ),
          if (messages.isNotEmpty)
            Positioned(
              bottom: 20,
              left: 0,
              right: 0,
              child: Container(
                height: 40,
                color: Colors.black.withOpacity(0.6),
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: Marquee(
                  text: messages.join("   ●   "),
                  style: const TextStyle(fontSize: 20, color: Colors.white),
                  scrollAxis: Axis.horizontal,
                  blankSpace: 100,
                  velocity: 30,
                  pauseAfterRound: Duration(seconds: 3),
                  startAfter: Duration(seconds: 1),
                  accelerationDuration: Duration(seconds: 1),
                  accelerationCurve: Curves.linear,
                  decelerationDuration: Duration(seconds: 1),
                  decelerationCurve: Curves.easeOut,
                ),
              ),
            ),
        ],
      ),
    );
  }
}

class MediaItem {
  final String type;
  final String url;
  MediaItem({required this.type, required this.url});
  factory MediaItem.fromJson(Map<String, dynamic> json) {
    return MediaItem(type: json['type'], url: json['url']);
  }
}
