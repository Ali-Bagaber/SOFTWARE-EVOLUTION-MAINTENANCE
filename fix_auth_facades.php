<?php

/**
 * Script to fix Auth:: facade usage in Blade templates
 * Replace Auth:: with auth() helper function
 */

function fixAuthFacades($directory) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $filePath = $file->getPathname();
            
            // Only process Blade files
            if (strpos($filePath, '.blade.php') !== false) {
                $content = file_get_contents($filePath);
                $originalContent = $content;
                
                // Replace various Auth:: patterns with auth() equivalents
                $replacements = [
                    'Auth::user()' => 'auth()->user()',
                    'Auth::check()' => 'auth()->check()',
                    'Auth::guest()' => 'auth()->guest()',
                    'Auth::id()' => 'auth()->id()',
                ];
                
                foreach ($replacements as $search => $replace) {
                    $content = str_replace($search, $replace, $content);
                }
                
                // Only write if content changed
                if ($content !== $originalContent) {
                    file_put_contents($filePath, $content);
                    echo "Fixed: " . $filePath . "\n";
                }
            }
        }
    }
}

// Fix Auth facades in views directory
$viewsDirectory = __DIR__ . '/resources/views';
if (is_dir($viewsDirectory)) {
    echo "Fixing Auth facades in Blade templates...\n";
    fixAuthFacades($viewsDirectory);
    echo "Done!\n";
} else {
    echo "Views directory not found: $viewsDirectory\n";
}
