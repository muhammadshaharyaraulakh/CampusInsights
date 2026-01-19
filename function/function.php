<?php

if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    header("Location: /pages/404.php");
    exit;
}
function blockDirectAccess() {
    if($_SERVER['REQUEST_METHOD'] === 'GET' ) {
        header("Location: /pages/404.php");
        exit;
    }
}
function blockAccess(){
    if(empty($_SESSION['id'])) {
        header("Location: /pages/404.php");
        exit;
}
}
function generateOtp(int $length = 16, string $characters = 'abcdefghijklmnopqrstuvwxyz0123456789'): string
{
    $otp = '';
    if (empty($characters)) {
        throw new Exception("Character set for OTP generation cannot be empty.");
    }
    
    $maxIndex = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[random_int(0, $maxIndex)];
    }

    return $otp;
};


function parse_user_agent_details($userAgent) {
    if (empty($userAgent) || strtolower($userAgent) === 'unknown') {
        return ['browser' => 'Unknown', 'os' => 'Unknown'];
    }

    $browser = 'Unknown Browser';
    $os = 'Unknown OS';

    if (preg_match('/Windows NT/i', $userAgent)) {
        $os = 'Windows';
    } elseif (preg_match('/Macintosh|Mac OS X/i', $userAgent)) {
        $os = 'macOS';
    } elseif (preg_match('/iPhone|iPad|iPod/i', $userAgent)) {
        $os = 'iOS';
    } elseif (preg_match('/Android/i', $userAgent)) {
        $os = 'Android';
    } elseif (preg_match('/Ubuntu/i', $userAgent)) {
        $os = 'Ubuntu';
    } elseif (preg_match('/Linux/i', $userAgent)) {
        $os = 'Linux'; 
    }

    if (preg_match('/Edg/i', $userAgent)) {
        $browser = 'Microsoft Edge';
    } elseif (preg_match('/Chrome/i', $userAgent)) {
        $browser = 'Chrome';
    } elseif (preg_match('/Firefox/i', $userAgent)) {
        $browser = 'Firefox';
    } elseif (preg_match('/Safari/i', $userAgent)) {
        $browser = 'Safari';
    } elseif (preg_match('/MSIE|Trident/i', $userAgent)) {
        $browser = 'Internet Explorer';
    }

    if (preg_match('/Chrome/i', $userAgent) && $browser === 'Safari') {
        $browser = 'Chrome';
    }

    if (in_array($os, ['Android', 'iOS']) && $browser === 'Unknown Browser') {
        $browser = 'Mobile Browser';
    }

    return ['browser' => $browser, 'os' => $os];
}
