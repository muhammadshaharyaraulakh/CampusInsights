<?php

/**
 * Generate a secure OTP or random string.
 * @param int $length Length of the OTP (default 16)
 * @param string|null $characters Characters to use (default lowercase letters + numbers)
 * @return string
 * @throws Exception
 */
function generateOtp(int $length = 16, string $characters = 'abcdefghijklmnopqrstuvwxyz0123456789'): string
{
    $otp = '';
    // Ensure the characters string is not empty
    if (empty($characters)) {
        throw new Exception("Character set for OTP generation cannot be empty.");
    }
    
    $maxIndex = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        // Use random_int for cryptographically secure random number generation
        $otp .= $characters[random_int(0, $maxIndex)];
    }

    return $otp;
};

/**
 * Parses a User Agent string to extract simplified Browser and OS details.
 * This version uses prioritized checking to ensure accuracy (e.g., handles Chrome/Safari ambiguity).
 * 
 * @param string $userAgent The full HTTP_USER_AGENT string.
 * @return array ['browser' => string, 'os' => string]
 */
function parse_user_agent_details($userAgent) {
    // Treat empty/unknown strings gracefully
    if (empty($userAgent) || strtolower($userAgent) === 'unknown') {
        return ['browser' => 'Unknown', 'os' => 'Unknown'];
    }

    $browser = 'Unknown Browser';
    $os = 'Unknown OS';

    // --- 1. OS Detection (Priority check) ---
    // Check for common platforms first
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
        // General Linux fallback for strings like 'X11; Linux x86_64'
        $os = 'Linux'; 
    }

    // --- 2. Browser Detection (Prioritized) ---
    // Check most specific tokens first
    if (preg_match('/Edg/i', $userAgent)) {
        $browser = 'Microsoft Edge';
    } elseif (preg_match('/Chrome/i', $userAgent)) {
        $browser = 'Chrome';
    } elseif (preg_match('/Firefox/i', $userAgent)) {
        $browser = 'Firefox';
    } elseif (preg_match('/Safari/i', $userAgent)) {
        // This will often catch Chrome first, but keeps Safari as a valid entry
        $browser = 'Safari';
    } elseif (preg_match('/MSIE|Trident/i', $userAgent)) {
        $browser = 'Internet Explorer';
    }
    
    // Ensure Chrome detection wins over Safari if both are present 
    // (If the parser got Chrome, it keeps Chrome. If it only got Safari, it keeps Safari.)
    if (preg_match('/Chrome/i', $userAgent) && $browser === 'Safari') {
        $browser = 'Chrome';
    }
    
    // Fallback for mobile OS
    if (in_array($os, ['Android', 'iOS']) && $browser === 'Unknown Browser') {
        $browser = 'Mobile Browser';
    }

    return ['browser' => $browser, 'os' => $os];
}