<?php

function has_access($slug) {
    // Ambil daftar permissions (array slug) dari session
    $permissions = session()->get('permissions') ?? [];
    
    // Cek apakah slug ada di dalam array tersebut
    return in_array($slug, $permissions);
}