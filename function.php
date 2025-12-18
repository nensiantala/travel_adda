<?php
function autoCategorize($title, $description) {
    $text = strtolower($title . " " . $description);

    $categories = [
        "Adventure"   => ["trek", "hiking", "rafting", "camping", "safari", "bungee"],
        "Beach"       => ["beach", "sea", "island", "coast", "goa", "maldives"],
        "Heritage"    => ["temple", "fort", "palace", "heritage", "history", "monument"],
        "Hill Station"=> ["mountain", "hill", "snow", "kashmir", "manali", "shimla", "nainital"],
        "Wildlife"    => ["jungle", "wildlife", "tiger", "sanctuary", "national park"],
        "Luxury"      => ["luxury", "resort", "spa", "villa", "5 star"],
        "Pilgrimage"  => ["pilgrimage", "yatra", "spiritual", "religious", "temple", "church", "mosque"]
    ];

    foreach ($categories as $category => $keywords) {
        foreach ($keywords as $word) {
            if (strpos($text, $word) !== false) {
                return $category;
            }
        }
    }

    return "Uncategorized"; // fallback
}
?>
