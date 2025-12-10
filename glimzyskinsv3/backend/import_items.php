<?php
/**
 * Skrypt do importu skinów z plików PNG
 * Uruchom: php import_items.php
 */

require_once 'db.php';

// Lista wszystkich skinów z folderu (na podstawie nazw plików)
$skins = [
    // Noże - Exceedingly Rare (0.1%)
    ['name' => 'Falchion Knife | Lore', 'weapon_type' => 'knife', 'image' => 'weapon_knife_falchion_cu_falchion_lore_light_png.png', 'rarity' => 1, 'price' => 500.00],
    ['name' => 'Gut Knife | Oiled', 'weapon_type' => 'knife', 'image' => 'weapon_knife_gut_aq_oiled_light_png.png', 'rarity' => 1, 'price' => 450.00],
    ['name' => 'Gut Knife | Tiger Orange', 'weapon_type' => 'knife', 'image' => 'weapon_knife_gut_an_tiger_orange_light_png.png', 'rarity' => 1, 'price' => 480.00],
    ['name' => 'Falchion Knife | Autotronic', 'weapon_type' => 'knife', 'image' => 'weapon_knife_falchion_gs_falchion_autotronic_light_png.png', 'rarity' => 1, 'price' => 550.00],
    ['name' => 'Bowie Knife', 'weapon_type' => 'knife', 'image' => 'weapon_knife_survival_bowie_png.png', 'rarity' => 1, 'price' => 520.00],
    
    // Rękawice - Exceedingly Rare (0.2%)
    ['name' => 'Sporty Gloves | Slingshot', 'weapon_type' => 'gloves', 'image' => 'sporty_gloves_sporty_slingshot_light_png.png', 'rarity' => 1, 'price' => 400.00],
    ['name' => 'Slick Gloves | Jaguar White', 'weapon_type' => 'gloves', 'image' => 'slick_gloves_slick_jaguar_white_light_png.png', 'rarity' => 1, 'price' => 420.00],
    ['name' => 'Sporty Gloves | Poison Frog Blue White', 'weapon_type' => 'gloves', 'image' => 'sporty_gloves_sporty_poison_frog_blue_white_light_png.png', 'rarity' => 1, 'price' => 410.00],
    ['name' => 'Sporty Gloves | Blue Pink', 'weapon_type' => 'gloves', 'image' => 'sporty_gloves_sporty_blue_pink_light_png.png', 'rarity' => 1, 'price' => 390.00],
    ['name' => 'Sporty Gloves | Military', 'weapon_type' => 'gloves', 'image' => 'sporty_gloves_sporty_military_light_png.png', 'rarity' => 1, 'price' => 400.00],
    ['name' => 'Sporty Gloves | Jaguar', 'weapon_type' => 'gloves', 'image' => 'sporty_gloves_sporty_jaguar_light_png.png', 'rarity' => 1, 'price' => 380.00],
    ['name' => 'Motorcycle Gloves | Mint Triangle', 'weapon_type' => 'gloves', 'image' => 'motorcycle_gloves_motorcycle_mint_triangle_light_png.png', 'rarity' => 1, 'price' => 395.00],
    ['name' => 'Specialist Gloves | Emerald Web', 'weapon_type' => 'gloves', 'image' => 'specialist_gloves_specialist_emerald_web_light_png.png', 'rarity' => 1, 'price' => 430.00],
    
    // Zeus - Exceedingly Rare (0.3%)
    ['name' => 'Zeus | Digicam Forest', 'weapon_type' => 'zeus', 'image' => 'weapon_taser_hy_digicam_forest_light_png.png', 'rarity' => 1, 'price' => 50.00],
    ['name' => 'Zeus | Electric Blue', 'weapon_type' => 'zeus', 'image' => 'weapon_taser_soe_electric_blue_light_png.png', 'rarity' => 1, 'price' => 55.00],
    ['name' => 'Zeus | Tosai', 'weapon_type' => 'zeus', 'image' => 'weapon_taser_zeus_tosai_light_png.png', 'rarity' => 1, 'price' => 60.00],
    ['name' => 'Zeus | Standard Issue', 'weapon_type' => 'zeus', 'image' => 'weapon_taser_zeus_standard_issue_light_png.png', 'rarity' => 1, 'price' => 45.00],
    ['name' => 'Zeus | Thunder God', 'weapon_type' => 'zeus', 'image' => 'weapon_taser_zeus_thunder_god_light_png.png', 'rarity' => 1, 'price' => 70.00],
    ['name' => 'Zeus | Overpass Dragon', 'weapon_type' => 'zeus', 'image' => 'weapon_taser_cu_overpass_dragon_zeus_light_png.png', 'rarity' => 1, 'price' => 65.00],
    
    // Covert (Red) - 2%
    ['name' => 'AK-47 | Fire Serpent', 'weapon_type' => 'rifle', 'image' => 'weapon_ak47_cu_fireserpent_ak47_bravo_light_png.png', 'rarity' => 2, 'price' => 150.00],
    ['name' => 'M4A1-S | Vertigo', 'weapon_type' => 'rifle', 'image' => 'weapon_m4a1_silencer_gs_m4a1_vertigo_light_png.png', 'rarity' => 2, 'price' => 140.00],
    ['name' => 'M4A1 | Kimono Sunrise', 'weapon_type' => 'rifle', 'image' => 'weapon_m4a1_am_kimono_sunrise_light_png.png', 'rarity' => 2, 'price' => 145.00],
    ['name' => 'Desert Eagle | Seastorm Blood', 'weapon_type' => 'pistol', 'image' => 'weapon_deagle_am_seastorm_blood_light_png.png', 'rarity' => 2, 'price' => 120.00],
    ['name' => 'M4A1-S | Snake', 'weapon_type' => 'rifle', 'image' => 'weapon_m4a1_silencer_cu_m4a1_snake_light_png.png', 'rarity' => 2, 'price' => 135.00],
    ['name' => 'Desert Eagle | Flames', 'weapon_type' => 'pistol', 'image' => 'weapon_deagle_aa_flames_light_png.png', 'rarity' => 2, 'price' => 125.00],
    ['name' => 'M4A1-S | Icarus', 'weapon_type' => 'rifle', 'image' => 'weapon_m4a1_silencer_hy_icarus_light_png.png', 'rarity' => 2, 'price' => 130.00],
    ['name' => 'M4A1-S | Blue Smoke', 'weapon_type' => 'rifle', 'image' => 'weapon_m4a1_silencer_am_m4a1s_bluesmoke_light_png.png', 'rarity' => 2, 'price' => 128.00],
    ['name' => 'AK-47 | Bamboo Jungle', 'weapon_type' => 'rifle', 'image' => 'weapon_ak47_am_bamboo_jungle_light_png.png', 'rarity' => 2, 'price' => 142.00],
    ['name' => 'AWP | Fade', 'weapon_type' => 'sniper', 'image' => 'weapon_awp_aa_awp_fade_light_png.png', 'rarity' => 2, 'price' => 160.00],
    ['name' => 'AK-47 | Tribute', 'weapon_type' => 'rifle', 'image' => 'weapon_ak47_cu_tribute_ak47_light_png.png', 'rarity' => 2, 'price' => 138.00],
    ['name' => 'P250 | Cybercroc', 'weapon_type' => 'pistol', 'image' => 'weapon_p250_cu_p250_cybercroc_light_png.png', 'rarity' => 2, 'price' => 110.00],
    ['name' => 'P90 | Cat Skulls', 'weapon_type' => 'smg', 'image' => 'weapon_p90_cu_catskulls_p90_light_png.png', 'rarity' => 2, 'price' => 115.00],
    ['name' => 'M4A1 | X-Ray', 'weapon_type' => 'rifle', 'image' => 'weapon_m4a1_cu_xray_m4_light_png.png', 'rarity' => 2, 'price' => 133.00],
    ['name' => 'USP-S | Noir', 'weapon_type' => 'pistol', 'image' => 'weapon_usp_silencer_cu_usps_noir_light_png.png', 'rarity' => 2, 'price' => 118.00],
    ['name' => 'SSG 08 | Dragonfire Scope', 'weapon_type' => 'sniper', 'image' => 'weapon_ssg08_cu_ssg08_dragonfire_scope_light_png.png', 'rarity' => 2, 'price' => 122.00],
    ['name' => 'AK-47 | Empress', 'weapon_type' => 'rifle', 'image' => 'weapon_ak47_gs_ak47_empress_light_png.png', 'rarity' => 2, 'price' => 148.00],
    ['name' => 'Glock | Gamma Doppler Phase 4', 'weapon_type' => 'pistol', 'image' => 'weapon_glock_am_gamma_doppler_phase4_glock_light_png.png', 'rarity' => 2, 'price' => 105.00],
    ['name' => 'Glock | Gamma Doppler Phase 3', 'weapon_type' => 'pistol', 'image' => 'weapon_glock_am_gamma_doppler_phase3_glock_light_png.png', 'rarity' => 2, 'price' => 103.00],
    ['name' => 'M4A1 | Asiimov', 'weapon_type' => 'rifle', 'image' => 'weapon_m4a1_cu_m4_asimov_medium_png.png', 'rarity' => 2, 'price' => 152.00],
    ['name' => 'FAMAS | Rally', 'weapon_type' => 'rifle', 'image' => 'weapon_famas_gs_famas_rally_light_png.png', 'rarity' => 2, 'price' => 100.00],
    ['name' => 'Glock | Gamma Doppler Phase 1', 'weapon_type' => 'pistol', 'image' => 'weapon_glock_am_gamma_doppler_phase1_glock_light_png.png', 'rarity' => 2, 'price' => 102.00],
    ['name' => 'Glock | Wasteland Rebel', 'weapon_type' => 'pistol', 'image' => 'weapon_glock_cu_glock_wasteland_rebel_light_png.png', 'rarity' => 2, 'price' => 108.00],
    ['name' => 'M4A1-S | Mecha Industries', 'weapon_type' => 'rifle', 'image' => 'weapon_m4a1_silencer_gs_m4a1_mecha_industries_light_png.png', 'rarity' => 2, 'price' => 136.00],
    ['name' => 'AK-47 | Abstract', 'weapon_type' => 'rifle', 'image' => 'weapon_ak47_gs_ak47_abstract_light_png.png', 'rarity' => 2, 'price' => 144.00],
    ['name' => 'M4A1-S | Hyper Beast', 'weapon_type' => 'rifle', 'image' => 'weapon_m4a1_silencer_cu_m4a1_hyper_beast_light_png.png', 'rarity' => 2, 'price' => 150.00],
    ['name' => 'M4A1-S | Soultaker', 'weapon_type' => 'rifle', 'image' => 'weapon_m4a1_silencer_cu_m4a1s_soultaker_light_png.png', 'rarity' => 2, 'price' => 147.00],
    ['name' => 'MAC-10 | Neon Rider', 'weapon_type' => 'smg', 'image' => 'weapon_mac10_cu_mac10_neonrider_light_png.png', 'rarity' => 2, 'price' => 112.00],
    ['name' => 'Galil AR | Abrasion', 'weapon_type' => 'rifle', 'image' => 'weapon_galilar_cu_galil_abrasion_medium_png.png', 'rarity' => 2, 'price' => 95.00],
    ['name' => 'AK-47 | Overpass Monster', 'weapon_type' => 'rifle', 'image' => 'weapon_ak47_cu_overpass_monster_ak47_light_png.png', 'rarity' => 2, 'price' => 146.00],
    ['name' => 'SCAR-20 | Intervention', 'weapon_type' => 'sniper', 'image' => 'weapon_scar20_cu_scar20_intervention_light_png.png', 'rarity' => 2, 'price' => 132.00],
    ['name' => 'MP9 | Narcis', 'weapon_type' => 'smg', 'image' => 'weapon_mp9_cu_mp9_narcis_light_png.png', 'rarity' => 2, 'price' => 107.00],
    ['name' => 'M4A1-S | Shatter', 'weapon_type' => 'rifle', 'image' => 'weapon_m4a1_silencer_gs_m4a1_shatter_light_png.png', 'rarity' => 2, 'price' => 134.00],
    ['name' => 'SG 556 | Reactor', 'weapon_type' => 'rifle', 'image' => 'weapon_sg556_cu_sg553_reactor_light_png.png', 'rarity' => 2, 'price' => 127.00],
    ['name' => 'AK-47 | T-Bus', 'weapon_type' => 'rifle', 'image' => 'weapon_ak47_ak47_t_bus_light_png.png', 'rarity' => 2, 'price' => 141.00],
    
    // Classified (Pink) - 5%
    ['name' => 'AUG | Bloom Blue', 'weapon_type' => 'rifle', 'image' => 'weapon_aug_am_bloom_blue_light_png.png', 'rarity' => 3, 'price' => 80.00],
    ['name' => 'P90 | Jorm Blue', 'weapon_type' => 'smg', 'image' => 'weapon_p90_am_jorm_blue_light_png.png', 'rarity' => 3, 'price' => 75.00],
    ['name' => 'AUG | Jorm Orange', 'weapon_type' => 'rifle', 'image' => 'weapon_aug_am_jorm_orange_light_png.png', 'rarity' => 3, 'price' => 78.00],
    ['name' => 'AK-47 | X-Ray', 'weapon_type' => 'rifle', 'image' => 'weapon_ak47_cu_ak_xray_light_png.png', 'rarity' => 3, 'price' => 82.00],
    ['name' => 'G3SG1 | Chronos', 'weapon_type' => 'sniper', 'image' => 'weapon_g3sg1_cu_chronos_g3sg1_light_png.png', 'rarity' => 3, 'price' => 85.00],
    ['name' => 'MAG-7 | Glass', 'weapon_type' => 'shotgun', 'image' => 'weapon_mag7_gs_mag7_glass_light_png.png', 'rarity' => 3, 'price' => 70.00],
    ['name' => 'SSG 08 | Flowers', 'weapon_type' => 'sniper', 'image' => 'weapon_ssg08_hy_flowers_stmarc_light_png.png', 'rarity' => 3, 'price' => 72.00],
    ['name' => 'Desert Eagle | Seastorm Shojo', 'weapon_type' => 'pistol', 'image' => 'weapon_deagle_am_seastorm_shojo_light_png.png', 'rarity' => 3, 'price' => 88.00],
    ['name' => 'Desert Eagle | Jorm Green', 'weapon_type' => 'pistol', 'image' => 'weapon_deagle_am_jorm_green_light_png.png', 'rarity' => 3, 'price' => 86.00],
    ['name' => 'Five-SeveN | Kimono Diamonds', 'weapon_type' => 'pistol', 'image' => 'weapon_fiveseven_hy_kimono_diamonds_light_png.png', 'rarity' => 3, 'price' => 84.00],
    ['name' => 'AWP | Lightning', 'weapon_type' => 'sniper', 'image' => 'weapon_awp_am_lightning_awp_light_png.png', 'rarity' => 3, 'price' => 90.00],
    
    // Restricted (Purple) - 15%
    ['name' => 'Nova | Hunter Blaze Orange', 'weapon_type' => 'shotgun', 'image' => 'weapon_nova_hy_hunter_blaze_orange_light_png.png', 'rarity' => 4, 'price' => 45.00],
    ['name' => 'MAG-7 | Chainmail', 'weapon_type' => 'shotgun', 'image' => 'weapon_mag7_am_chainmail_light_png.png', 'rarity' => 4, 'price' => 48.00],
    ['name' => 'Negev | Phoenix Tags Red', 'weapon_type' => 'lmg', 'image' => 'weapon_negev_hy_phoenix_tags_red_light_png.png', 'rarity' => 4, 'price' => 50.00],
    ['name' => 'Sawed-Off | Green Leather', 'weapon_type' => 'shotgun', 'image' => 'weapon_sawedoff_cu_green_leather_sawedoff_light_png.png', 'rarity' => 4, 'price' => 42.00],
    ['name' => 'XM1014 | Leather', 'weapon_type' => 'shotgun', 'image' => 'weapon_xm1014_cu_leather_xm1014_light_png.png', 'rarity' => 4, 'price' => 44.00],
    ['name' => 'XM1014 | Knots Silver', 'weapon_type' => 'shotgun', 'image' => 'weapon_xm1014_am_knots_silver_light_png.png', 'rarity' => 4, 'price' => 46.00],
    ['name' => 'Sawed-Off | Octopump', 'weapon_type' => 'shotgun', 'image' => 'weapon_sawedoff_cu_sawedoff_octopump_light_png.png', 'rarity' => 4, 'price' => 43.00],
    ['name' => 'Nova | Veneto', 'weapon_type' => 'shotgun', 'image' => 'weapon_nova_am_veneto2_light_png.png', 'rarity' => 4, 'price' => 41.00],
    ['name' => 'Sawed-Off | Copper', 'weapon_type' => 'shotgun', 'image' => 'weapon_sawedoff_aq_copper_light_png.png', 'rarity' => 4, 'price' => 47.00],
    ['name' => 'CZ75-Auto | Crystallized Green', 'weapon_type' => 'pistol', 'image' => 'weapon_cz75a_am_crystallized_green_light_png.png', 'rarity' => 4, 'price' => 52.00],
    ['name' => 'Five-SeveN | Bud Red', 'weapon_type' => 'pistol', 'image' => 'weapon_fiveseven_hy_bud_red_light_png.png', 'rarity' => 4, 'price' => 49.00],
    ['name' => 'Desert Eagle | Vertigo', 'weapon_type' => 'pistol', 'image' => 'weapon_deagle_aa_vertigo_light_png.png', 'rarity' => 4, 'price' => 55.00],
    ['name' => 'CZ75-Auto | Royal', 'weapon_type' => 'pistol', 'image' => 'weapon_cz75a_am_royal_light_png.png', 'rarity' => 4, 'price' => 53.00],
    ['name' => 'USP-S | Intelligence Magenta', 'weapon_type' => 'pistol', 'image' => 'weapon_usp_silencer_am_intelligence_magenta_light_png.png', 'rarity' => 4, 'price' => 51.00],
    ['name' => 'Desert Eagle | Scales Bravo', 'weapon_type' => 'pistol', 'image' => 'weapon_deagle_am_scales_bravo_light_png.png', 'rarity' => 4, 'price' => 54.00],
    ['name' => 'Desert Eagle | Handcannon', 'weapon_type' => 'pistol', 'image' => 'weapon_deagle_aq_handcannon_light_png.png', 'rarity' => 4, 'price' => 56.00],
    ['name' => 'Glock | Aqua Flecks', 'weapon_type' => 'pistol', 'image' => 'weapon_glock_am_aqua_flecks_light_png.png', 'rarity' => 4, 'price' => 48.00],
    ['name' => 'Dual Berettas | Mother of Pearl', 'weapon_type' => 'pistol', 'image' => 'weapon_elite_gs_mother_of_pearl_elite_light_png.png', 'rarity' => 4, 'price' => 50.00],
    ['name' => 'Glock | Leaf Blue', 'weapon_type' => 'pistol', 'image' => 'weapon_glock_hy_leaf_blue_light_png.png', 'rarity' => 4, 'price' => 47.00],
    ['name' => 'AWP | Crumple Bravo', 'weapon_type' => 'sniper', 'image' => 'weapon_awp_am_crumple_bravo_light_png.png', 'rarity' => 4, 'price' => 58.00],
    ['name' => 'AWP | Tigers Blue', 'weapon_type' => 'sniper', 'image' => 'weapon_awp_am_tigers_blue_light_png.png', 'rarity' => 4, 'price' => 60.00],
    ['name' => 'SG 556 | Yellow', 'weapon_type' => 'rifle', 'image' => 'weapon_sg556_so_yellow_light_png.png', 'rarity' => 4, 'price' => 45.00],
    ['name' => 'AK-47 | Oiled', 'weapon_type' => 'rifle', 'image' => 'weapon_ak47_aq_oiled_light_png.png', 'rarity' => 4, 'price' => 52.00],
    ['name' => 'UMP-45 | Crime Scene', 'weapon_type' => 'smg', 'image' => 'weapon_ump45_cu_ump_crime_scene_light_png.png', 'rarity' => 4, 'price' => 46.00],
    ['name' => 'MP7 | Bud Green', 'weapon_type' => 'smg', 'image' => 'weapon_mp7_sp_bud_green_light_png.png', 'rarity' => 4, 'price' => 44.00],
    ['name' => 'MP9 | Stained Glass', 'weapon_type' => 'smg', 'image' => 'weapon_mp9_am_stained_glass_light_png.png', 'rarity' => 4, 'price' => 43.00],
    ['name' => 'MP9 | Yellow', 'weapon_type' => 'smg', 'image' => 'weapon_mp9_so_yellow_light_png.png', 'rarity' => 4, 'price' => 42.00],
    ['name' => 'MAC-10 | Knots Brown', 'weapon_type' => 'smg', 'image' => 'weapon_mac10_am_knots_brown_light_png.png', 'rarity' => 4, 'price' => 41.00],
    ['name' => 'UMP-45 | Labyrinth', 'weapon_type' => 'smg', 'image' => 'weapon_ump45_cu_labyrinth_light_png.png', 'rarity' => 4, 'price' => 48.00],
    ['name' => 'MP9 | Pandora', 'weapon_type' => 'smg', 'image' => 'weapon_mp9_aa_pandora_light_png.png', 'rarity' => 4, 'price' => 47.00],
    ['name' => 'MAC-10 | Snake', 'weapon_type' => 'smg', 'image' => 'weapon_mac10_gs_mac10_snake_light_png.png', 'rarity' => 4, 'price' => 45.00],
    ['name' => 'MP9 | Red', 'weapon_type' => 'smg', 'image' => 'weapon_mp9_an_red_light_png.png', 'rarity' => 4, 'price' => 40.00],
    ['name' => 'Negev | Navy', 'weapon_type' => 'lmg', 'image' => 'weapon_negev_an_navy_light_png.png', 'rarity' => 4, 'price' => 49.00],
    ['name' => 'AUG | Red', 'weapon_type' => 'rifle', 'image' => 'weapon_aug_an_red_light_png.png', 'rarity' => 4, 'price' => 51.00],
    
    // Mil-Spec (Blue) - 35%
    ['name' => 'M4A1-S | Fade', 'weapon_type' => 'rifle', 'image' => 'weapon_m4a1_silencer_aa_fade_m4a1s_light_png.png', 'rarity' => 5, 'price' => 25.00],
    ['name' => 'AWP | Fade', 'weapon_type' => 'sniper', 'image' => 'weapon_awp_aa_awp_fade_light_png.png', 'rarity' => 5, 'price' => 30.00],
    
    // Consumer Grade (Light Blue) - 42.9%
    // Dodaj więcej skinów consumer grade jeśli potrzebujesz
];

try {
    $pdo->beginTransaction();
    
    // Usuń stare dane (opcjonalnie)
    // $pdo->exec("DELETE FROM case_items");
    // $pdo->exec("DELETE FROM items");
    
    $inserted = 0;
    foreach ($skins as $skin) {
        $stmt = $pdo->prepare("INSERT INTO items (name, weapon_type, image_path, rarity_id, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $skin['name'],
            $skin['weapon_type'],
            $skin['image'],
            $skin['rarity'],
            $skin['price']
        ]);
        $inserted++;
    }
    
    // Dodaj wszystkie przedmioty do pierwszej skrzynki z odpowiednimi szansami
    $caseId = 1;
    $rarities = [
        1 => 0.10, // Exceedingly Rare
        2 => 2.00, // Covert
        3 => 5.00, // Classified
        4 => 15.00, // Restricted
        5 => 35.00, // Mil-Spec
        6 => 42.90 // Consumer Grade
    ];
    
    // Pobierz wszystkie przedmioty
    $items = $pdo->query("SELECT id, rarity_id FROM items")->fetchAll();
    
    // Oblicz szansę dla każdego przedmiotu w oparciu o rzadkość
    foreach ($items as $item) {
        $rarityId = $item['rarity_id'];
        $baseChance = $rarities[$rarityId];
        
        // Policz ile jest przedmiotów tej samej rzadkości
        $countStmt = $pdo->prepare("SELECT COUNT(*) as count FROM items WHERE rarity_id = ?");
        $countStmt->execute([$rarityId]);
        $count = $countStmt->fetch()['count'];
        
        // Szansa = bazowa szansa / liczba przedmiotów tej rzadkości
        $chance = $count > 0 ? $baseChance / $count : 0;
        
        $stmt = $pdo->prepare("INSERT INTO case_items (case_id, item_id, chance_percent) VALUES (?, ?, ?)");
        $stmt->execute([$caseId, $item['id'], $chance]);
    }
    
    $pdo->commit();
    
    echo "Sukces! Zaimportowano $inserted przedmiotów do bazy danych.\n";
    echo "Wszystkie przedmioty zostały dodane do skrzynki #$caseId z odpowiednimi szansami.\n";
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Błąd: " . $e->getMessage() . "\n";
}

