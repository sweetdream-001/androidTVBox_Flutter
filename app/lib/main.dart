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
  List<String> newsHeadlines = [];

  @override
  void initState() {
    super.initState();
    fetchPlaylist();
    fetchMessages();
    fetchNews(); // <-- add this line
  }


  Future<void> fetchNews() async {
    final response = await http.get(Uri.parse('http://localhost/main/api/news'));
    
    if (response.statusCode == 200) {
      // final Map<String, dynamic> data = jsonDecode(response.body);
      // final List<dynamic> items = data['data'];
      final data = jsonDecode(response.body) as Map<String, dynamic>;
      final List<dynamic> items = data['data'];
      final headlines = items.map((item) => item['title'] as String).toList();

      setState(() {
        newsHeadlines = headlines;
      });
    } else {
      print('Failed to load news headlines');
    }
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
                        placeholder: (context, url) =>
                            const CircularProgressIndicator(),
                        errorWidget: (context, url, error) =>
                            const Icon(Icons.error),
                      )
                    : const CircularProgressIndicator(),
          ),
          if (messages.isNotEmpty)
            Positioned(
              top: 0,
              left: 0,
              right: 0,
              child: Container(
                height: 30,
                color: Colors.blueGrey.shade900,
                child: Marquee(
                  text: messages.join("   ●   "),
                  style: const TextStyle(fontSize: 16, color: Colors.white),
                  blankSpace: 100,
                  velocity: 30,
                ),
              ),
            ),
          if (newsHeadlines.isNotEmpty)
            Positioned(
              bottom: 0,
              left: 0,
              right: 0,
              child: Container(
                height: 30,
                color: Colors.black.withOpacity(0.7),
                child: Marquee(
                  text: newsHeadlines.join("   ●   "),
                  style:
                      const TextStyle(fontSize: 16, color: Colors.orangeAccent),
                  blankSpace: 100,
                  velocity: 30,
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
