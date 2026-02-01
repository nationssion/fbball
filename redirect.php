<?php
// redirect.php - Android Intent Redirect with Full Tracking

// Get ALL tracking parameters
$clickid = $_GET['clickid'] ?? $_GET['sub1'] ?? '';
$sub1 = $_GET['sub1'] ?? '';
$sub2 = $_GET['sub2'] ?? '';
$sub3 = $_GET['sub3'] ?? '';
$sub4 = $_GET['sub4'] ?? '';
$sub5 = $_GET['sub5'] ?? '';
$cid = $_GET['cid'] ?? '';
$sid = $_GET['sid'] ?? '';

// Your offer URL (REPLACE THIS)
$baseUrl = 'https://your-offer-url.com';

// Build offer URL with tracking
$offerUrl = $baseUrl . '?';
$params = [];
if ($clickid) $params[] = 'clickid=' . urlencode($clickid);
if ($sub1) $params[] = 'sub1=' . urlencode($sub1);
if ($sub2) $params[] = 'sub2=' . urlencode($sub2);
if ($sub3) $params[] = 'sub3=' . urlencode($sub3);
if ($sub4) $params[] = 'sub4=' . urlencode($sub4);
if ($sub5) $params[] = 'sub5=' . urlencode($sub5);
if ($cid) $params[] = 'cid=' . urlencode($cid);
if ($sid) $params[] = 'sid=' . urlencode($sid);

$offerUrl .= implode('&', $params);

// Parse offer URL for intent
$parsed = parse_url($offerUrl);
$host = $parsed['host'];
$path = $parsed['path'] ?? '';
$query = $parsed['query'] ?? '';

// Build Android Intent
$intentUrl = "intent://{$host}{$path}?{$query}#Intent;scheme=https;package=com.android.chrome;S.browser_fallback_url=" . urlencode($offerUrl) . ";end";

// Detect platform
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$isAndroid = stripos($userAgent, 'Android') !== false;
$isIOS = stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false;

// Redirect
if ($isAndroid) {
    header("Location: {$intentUrl}", true, 302);
    exit();
} elseif ($isIOS) {
    $chromeUrl = "googlechrome://navigate?url=" . urlencode($offerUrl);
    header("Location: {$chromeUrl}", true, 302);
    exit();
} else {
    header("Location: {$offerUrl}", true, 302);
    exit();
}
?>
