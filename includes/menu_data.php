<?php
// ============================================================
// includes/menu_data.php — PHP arrays holding menu items
// Used in index.php to loop & render menu cards with PHP
// ============================================================

$menuItems = [
    // ── MAIN DISHES ──────────────────────────────────────────
    [
        'id'       => 1,
        'cat'      => 'main',
        'tag'      => 'Masak Lemak Cili Api',
        'name'     => 'Smoked Chicken Masak Lemak Cili Api',
        'desc'     => 'Smoked chicken in a rich, spicy coconut milk gravy',
        'price'    => 29.90,
        'img'      => 'image/Resepi Ayam Masak Lemak Cili Api Negeri Sembilan.jpg.jpeg',
    ],
    [
        'id'       => 2,
        'cat'      => 'main',
        'tag'      => 'Masak Lemak Cili Api',
        'name'     => 'Smoked Beef Masak Lemak Cili Api',
        'desc'     => 'Tender smoked beef in a full-bodied coconut chilli gravy',
        'price'    => 35.90,
        'img'      => 'image/daging masak lemak.jpg',
    ],
    [
        'id'       => 3,
        'cat'      => 'main',
        'tag'      => 'Sambal Tumis Petai',
        'name'     => 'Prawns Sambal Tumis with Petai',
        'desc'     => 'Fresh prawns stir-fried with stink beans in fragrant sambal',
        'price'    => 39.90,
        'img'      => 'image/sambal udang.jpg',
    ],
    [
        'id'       => 4,
        'cat'      => 'main',
        'tag'      => 'Sambal Berlada',
        'name'     => 'Sea Bass (Siakap) Sambal Berlado',
        'desc'     => 'Fresh sea bass cooked in bold and fiery sambal berlada',
        'price'    => 68.90,
        'img'      => 'image/ikan siakap belado.jpg.jpeg',
    ],
    [
        'id'       => 5,
        'cat'      => 'main',
        'tag'      => 'Starter',
        'name'     => 'Cucur Udang (Prawn Fritters)',
        'desc'     => 'Crispy prawn fritters packed with fresh whole prawns',
        'price'    => 16.90,
        'img'      => 'image/Shrimp Fritter.jpg.jpeg',
    ],
    [
        'id'       => 6,
        'cat'      => 'main',
        'tag'      => 'Soy Sauce',
        'name'     => 'Fried Egg in Soy Sauce',
        'desc'     => 'Sunny-side-up egg cooked in a sweet soy sauce reduction',
        'price'    => 15.90,
        'img'      => 'image/TelurMasakKicap.jpg.jpeg',
    ],
    [
        'id'       => 7,
        'cat'      => 'main',
        'tag'      => 'Egg',
        'name'     => 'Telur Dadar (Malay Omelette)',
        'desc'     => 'Fluffy Malay-style omelette, golden and crispy on the edges',
        'price'    => 9.90,
        'img'      => 'image/Telur Dadar.jpg.jpeg',
    ],
    [
        'id'       => 8,
        'cat'      => 'main',
        'tag'      => 'Green Sambal Petai',
        'name'     => 'Eggplant with Green Sambal Petai',
        'desc'     => 'Soft eggplant cooked in aromatic green sambal with stink beans',
        'price'    => 17.90,
        'img'      => 'image/SAMBAL TERUNG.jpg.jpeg',
    ],

    // ── SIDES & VEGETABLES ───────────────────────────────────
    [
        'id'       => 9,
        'cat'      => 'sides',
        'tag'      => 'Vegetables',
        'name'     => 'Vegetables in White Coconut Gravy',
        'desc'     => 'Mixed vegetables simmered in a light and creamy coconut broth',
        'price'    => 18.90,
        'img'      => 'image/Vegetable Stew -coconut gravy.jpg.jpeg',
    ],
    [
        'id'       => 10,
        'cat'      => 'sides',
        'tag'      => 'Sides',
        'name'     => 'Ulam with Sambal Belacan',
        'desc'     => 'Fresh traditional raw herbs served with house sambal belacan',
        'price'    => 12.90,
        'img'      => 'image/UlamDanSambalbelacan.jpg.jpeg',
    ],
    [
        'id'       => 11,
        'cat'      => 'sides',
        'tag'      => 'Condiment',
        'name'     => 'Sambal Tempoyak',
        'desc'     => 'Traditional fermented durian sambal, thick and intensely flavourful',
        'price'    => 4.90,
        'img'      => 'image/Sambal Tempoyak.jpg.jpeg',
    ],

    // ── RICE ─────────────────────────────────────────────────
    [
        'id'       => 12,
        'cat'      => 'rice',
        'tag'      => 'Rice',
        'name'     => 'Steamed White Rice',
        'desc'     => 'Perfectly cooked fragrant white rice, served per plate',
        'price'    => 3.90,
        'img'      => 'image/nasi putih.jpg.jpeg',
    ],

    // ── KIDS' SET ─────────────────────────────────────────────
    [
        'id'       => 13,
        'cat'      => 'kids',
        'tag'      => "Kids' Set",
        'name'     => "Kids' Soy Sauce Chicken Set",
        'desc'     => 'A special set for children with soy sauce chicken and rice',
        'price'    => 19.90,
        'img'      => 'image/Kids chicken rice set.jpeg',
    ],
    [
        'id'       => 14,
        'cat'      => 'kids',
        'tag'      => "Kids' Set",
        'name'     => "Kids' Chicken Soup Set",
        'desc'     => 'A light and nourishing chicken soup set specially for the little ones',
        'price'    => 17.90,
        'img'      => 'image/Kids chicken soup.jpg.jpeg',
    ],
];

// ── Helper: get unique category list ─────────────────────────
function getCategories(array $items): array {
    $cats = [];
    foreach ($items as $item) {
        if (!in_array($item['cat'], $cats)) {
            $cats[] = $item['cat'];
        }
    }
    return $cats;
}
?>
