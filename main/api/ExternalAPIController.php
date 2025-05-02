<?php
class ExternalAPIController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getWeather() {
        $url = $this->getActiveApiUrl('weather');
        if (!$url) {
            $this->respondError('Weather API is inactive or not found.');
            return;
        }

        $data = $this->fetchApi($url);
        echo $this->formatResponse($data);
    }

    public function getNews() {
        $url = $this->getActiveApiUrl('news');
        if (!$url) {
            $this->respondError('News API is inactive or not found.');
            return;
        }

        $data = $this->fetchApi($url);
        echo $this->formatResponse($data);
    }

    public function generateWeatherAds() {
        $url = $this->getActiveApiUrl('weather');
        if (!$url) {
            return ['Weather API is inactive or not configured.'];
        }
    
        $json = $this->fetchApi($url);
        $data = json_decode($json, true);
        if (!$data || !isset($data['current_condition'][0])) {
            return ['Failed to fetch weather data.'];
        }

        $ads = [];
        $current = $data['current_condition'][0];
        $today = $data['weather'][0];
        $location = $data['nearest_area'][0]['areaName'][0]['value'] ?? 'your area';
        $region = $data['nearest_area'][0]['region'][0]['value'] ?? '';
        $country = $data['nearest_area'][0]['country'][0]['value'] ?? '';
    
        $tempC = (int)$current['temp_C'];
        $humidity = (int)$current['humidity'];
        $uv = (int)$current['uvIndex'];
        $desc = $current['weatherDesc'][0]['value'] ?? '';
        $rainChance = (int)($today['hourly'][0]['chanceofrain'] ?? 0);
        $feelsLike = $current['FeelsLikeC'] ?? $tempC;
    
        // 🌡️ General weather summary
        $ads[] = "📍 Weather in $location, $region, $country: $desc, $tempC °C (feels like $feelsLike °C), humidity $humidity%, UV index $uv.";
    
        // 🔥 Hot or cold
        if ($tempC >= 32) {
            $ads[] = "🥵 Hot day in $location! Stay cool with drinks and shaded spots!";
        } elseif ($tempC <= 16) {
            $ads[] = "🧣 It's cold in $location. Warm clothes on sale nearby!";
        }
    
        // ☀️ UV alert
        if ($uv >= 6) {
            $ads[] = "☀️ High UV levels detected. Don't forget sunscreen and shades!";
        }
    
        // 💧 Humidity
        if ($humidity >= 75) {
            $ads[] = "💦 Humid day ahead — Stay fresh with hydrating beverages!";
        }
    
        // ☔ Rain chance
        if ($rainChance >= 60) {
            $ads[] = "☔ Chance of rain is $rainChance%. Grab your umbrellas at our store!";
        }
    
        // 🌫 Fog or mist
        if (str_contains(strtolower($desc), 'fog') || str_contains(strtolower($desc), 'mist')) {
            $ads[] = "🌫 Foggy conditions in $location — Drive safe and keep your lights on!";
        }
    
        return $ads;
    }
    

    // ✅ Check if API with this name exists and is active
    private function getActiveApiUrl($name) {
        $stmt = $this->conn->prepare("SELECT url FROM api_integrations WHERE name = ? AND status = 1 LIMIT 1");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    
        $result = $stmt->get_result(); // ✅ safer way
        $url = null;
    
        if ($row = $result->fetch_assoc()) {
            $url = $row['url'];
        }
    
        $stmt->close();
        return $url;
    }

    private function fetchApi($url) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'PHP API Client',
        ]);
        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        return ($code === 200) ? $response : json_encode([
            'error' => 'Failed to fetch data',
            'http_code' => $code,
            'message' => $error
        ]);
    }

    private function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    private function formatResponse($raw) {
        return $this->isJson($raw)
            ? $raw
            : json_encode(['data' => $raw, 'format' => 'text/xml']);
    }

    private function respondError($msg) {
        http_response_code(404);
        echo json_encode(['error' => $msg]);
    }
}