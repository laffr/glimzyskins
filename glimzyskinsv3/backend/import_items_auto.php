<?php
/**
 * Automatyczny skrypt do importu skinów z plików PNG
 * Skanuje folder i automatycznie tworzy przedmioty na podstawie nazw plików
 * 
 * Uruchom: php import_items_auto.php
 * Lub otwórz w przeglądarce: http://localhost/glimzyskins/import_items_auto.php
 */

require_once 'db.php';

// Ścieżka do folderu z obrazami (rozpakuj pliki z skiny.rar do tego folderu)
$imagesPath = 'C:/xampp/htdocs/glimzyskins/skiny/';
// Ścieżka względna dla bazy danych (URL dla aplikacji Android)
$imageUrlPath = 'http://10.0.2.2/glimzyskins/skiny/';

// Funkcja do parsowania nazwy pliku i wyodrębnienia informacji
function parseFileName($filename) {
    $info = [
        'weapon_type' => 'unknown',
        'name' => '',
        'rarity' => 6, // Domyślnie Consumer Grade
        'price' => 5.00
    ];
    
    $filename = strtolower($filename);
    
    // Noże - Exceedingly Rare (0.1%)
    if (strpos($filename, 'weapon_knife') !== false || strpos($filename, 'knife_') !== false) {
        $info['weapon_type'] = 'knife';
        $info['rarity'] = 1; // Exceedingly Rare
        $info['price'] = rand(400, 600);
        
        // Parsuj nazwę noża
        if (strpos($filename, 'falchion') !== false) {
            if (strpos($filename, 'lore') !== false) $info['name'] = 'Falchion Knife | Lore';
            elseif (strpos($filename, 'autotronic') !== false) $info['name'] = 'Falchion Knife | Autotronic';
            else $info['name'] = 'Falchion Knife';
        } elseif (strpos($filename, 'gut') !== false) {
            if (strpos($filename, 'oiled') !== false) $info['name'] = 'Gut Knife | Oiled';
            elseif (strpos($filename, 'tiger') !== false) $info['name'] = 'Gut Knife | Tiger Orange';
            else $info['name'] = 'Gut Knife';
        } elseif (strpos($filename, 'bowie') !== false || strpos($filename, 'survival') !== false) {
            $info['name'] = 'Bowie Knife';
        } else {
            $info['name'] = 'Knife';
        }
    }
    // Rękawice - Exceedingly Rare (0.1%)
    elseif (strpos($filename, 'gloves') !== false) {
        $info['weapon_type'] = 'gloves';
        $info['rarity'] = 1; // Exceedingly Rare
        $info['price'] = rand(380, 450);
        
        if (strpos($filename, 'sporty') !== false) {
            if (strpos($filename, 'slingshot') !== false) $info['name'] = 'Sporty Gloves | Slingshot';
            elseif (strpos($filename, 'poison_frog') !== false) $info['name'] = 'Sporty Gloves | Poison Frog Blue White';
            elseif (strpos($filename, 'blue_pink') !== false) $info['name'] = 'Sporty Gloves | Blue Pink';
            elseif (strpos($filename, 'military') !== false) $info['name'] = 'Sporty Gloves | Military';
            elseif (strpos($filename, 'jaguar') !== false) $info['name'] = 'Sporty Gloves | Jaguar';
            else $info['name'] = 'Sporty Gloves';
        } elseif (strpos($filename, 'slick') !== false) {
            $info['name'] = 'Slick Gloves | Jaguar White';
        } elseif (strpos($filename, 'motorcycle') !== false) {
            $info['name'] = 'Motorcycle Gloves | Mint Triangle';
        } elseif (strpos($filename, 'specialist') !== false) {
            $info['name'] = 'Specialist Gloves | Emerald Web';
        } else {
            $info['name'] = 'Gloves';
        }
    }
    // Zeus/Taser - Exceedingly Rare (0.1%)
    elseif (strpos($filename, 'taser') !== false || strpos($filename, 'zeus') !== false) {
        $info['weapon_type'] = 'zeus';
        $info['rarity'] = 1; // Exceedingly Rare
        $info['price'] = rand(45, 70);
        
        if (strpos($filename, 'digicam') !== false) $info['name'] = 'Zeus | Digicam Forest';
        elseif (strpos($filename, 'electric_blue') !== false) $info['name'] = 'Zeus | Electric Blue';
        elseif (strpos($filename, 'tosai') !== false) $info['name'] = 'Zeus | Tosai';
        elseif (strpos($filename, 'standard_issue') !== false) $info['name'] = 'Zeus | Standard Issue';
        elseif (strpos($filename, 'thunder_god') !== false) $info['name'] = 'Zeus | Thunder God';
        elseif (strpos($filename, 'overpass_dragon') !== false) $info['name'] = 'Zeus | Overpass Dragon';
        else $info['name'] = 'Zeus';
    }
    // Covert (Red) - 2%
    elseif (strpos($filename, 'fireserpent') !== false || strpos($filename, 'vertigo') !== false || 
            strpos($filename, 'kimono_sunrise') !== false || strpos($filename, 'seastorm_blood') !== false ||
            strpos($filename, 'm4a1_snake') !== false || strpos($filename, 'flames') !== false ||
            strpos($filename, 'icarus') !== false || strpos($filename, 'bluesmoke') !== false ||
            strpos($filename, 'bamboo_jungle') !== false || strpos($filename, 'awp_fade') !== false ||
            strpos($filename, 'tribute') !== false || strpos($filename, 'cybercroc') !== false ||
            strpos($filename, 'catskulls') !== false || strpos($filename, 'xray_m4') !== false ||
            strpos($filename, 'usps_noir') !== false || strpos($filename, 'dragonfire_scope') !== false ||
            strpos($filename, 'empress') !== false || strpos($filename, 'gamma_doppler') !== false ||
            strpos($filename, 'asimov') !== false || strpos($filename, 'rally') !== false ||
            strpos($filename, 'wasteland_rebel') !== false || strpos($filename, 'mecha_industries') !== false ||
            strpos($filename, 'abstract') !== false || strpos($filename, 'hyper_beast') !== false ||
            strpos($filename, 'soultaker') !== false || strpos($filename, 'neonrider') !== false ||
            strpos($filename, 'abrasion') !== false || strpos($filename, 'overpass_monster') !== false ||
            strpos($filename, 'intervention') !== false || strpos($filename, 'narcis') !== false ||
            strpos($filename, 'shatter') !== false || strpos($filename, 'reactor') !== false ||
            strpos($filename, 't_bus') !== false) {
        $info['rarity'] = 2; // Covert
        $info['price'] = rand(95, 160);
        
        // Określ typ broni
        if (strpos($filename, 'ak47') !== false) {
            $info['weapon_type'] = 'rifle';
            if (strpos($filename, 'fireserpent') !== false) $info['name'] = 'AK-47 | Fire Serpent';
            elseif (strpos($filename, 'bamboo_jungle') !== false) $info['name'] = 'AK-47 | Bamboo Jungle';
            elseif (strpos($filename, 'tribute') !== false) $info['name'] = 'AK-47 | Tribute';
            elseif (strpos($filename, 'empress') !== false) $info['name'] = 'AK-47 | Empress';
            elseif (strpos($filename, 'abstract') !== false) $info['name'] = 'AK-47 | Abstract';
            elseif (strpos($filename, 'overpass_monster') !== false) $info['name'] = 'AK-47 | Overpass Monster';
            elseif (strpos($filename, 't_bus') !== false) $info['name'] = 'AK-47 | T-Bus';
            elseif (strpos($filename, 'xray') !== false) $info['name'] = 'AK-47 | X-Ray';
            else $info['name'] = 'AK-47';
        } elseif (strpos($filename, 'm4a1') !== false) {
            $info['weapon_type'] = 'rifle';
            if (strpos($filename, 'vertigo') !== false) $info['name'] = 'M4A1-S | Vertigo';
            elseif (strpos($filename, 'kimono_sunrise') !== false) $info['name'] = 'M4A1 | Kimono Sunrise';
            elseif (strpos($filename, 'snake') !== false) $info['name'] = 'M4A1-S | Snake';
            elseif (strpos($filename, 'icarus') !== false) $info['name'] = 'M4A1-S | Icarus';
            elseif (strpos($filename, 'bluesmoke') !== false) $info['name'] = 'M4A1-S | Blue Smoke';
            elseif (strpos($filename, 'xray') !== false) $info['name'] = 'M4A1 | X-Ray';
            elseif (strpos($filename, 'asimov') !== false) $info['name'] = 'M4A1 | Asiimov';
            elseif (strpos($filename, 'mecha_industries') !== false) $info['name'] = 'M4A1-S | Mecha Industries';
            elseif (strpos($filename, 'hyper_beast') !== false) $info['name'] = 'M4A1-S | Hyper Beast';
            elseif (strpos($filename, 'soultaker') !== false) $info['name'] = 'M4A1-S | Soultaker';
            elseif (strpos($filename, 'shatter') !== false) $info['name'] = 'M4A1-S | Shatter';
            elseif (strpos($filename, 'fade') !== false) $info['name'] = 'M4A1-S | Fade';
            else $info['name'] = 'M4A1';
        } elseif (strpos($filename, 'awp') !== false) {
            $info['weapon_type'] = 'sniper';
            if (strpos($filename, 'fade') !== false) $info['name'] = 'AWP | Fade';
            else $info['name'] = 'AWP';
        } elseif (strpos($filename, 'deagle') !== false) {
            $info['weapon_type'] = 'pistol';
            if (strpos($filename, 'seastorm_blood') !== false) $info['name'] = 'Desert Eagle | Seastorm Blood';
            elseif (strpos($filename, 'flames') !== false) $info['name'] = 'Desert Eagle | Flames';
            else $info['name'] = 'Desert Eagle';
        } elseif (strpos($filename, 'p250') !== false) {
            $info['weapon_type'] = 'pistol';
            $info['name'] = 'P250 | Cybercroc';
        } elseif (strpos($filename, 'p90') !== false) {
            $info['weapon_type'] = 'smg';
            $info['name'] = 'P90 | Cat Skulls';
        } elseif (strpos($filename, 'usp') !== false) {
            $info['weapon_type'] = 'pistol';
            $info['name'] = 'USP-S | Noir';
        } elseif (strpos($filename, 'ssg08') !== false) {
            $info['weapon_type'] = 'sniper';
            $info['name'] = 'SSG 08 | Dragonfire Scope';
        } elseif (strpos($filename, 'glock') !== false) {
            $info['weapon_type'] = 'pistol';
            if (strpos($filename, 'gamma_doppler') !== false) {
                if (strpos($filename, 'phase4') !== false) $info['name'] = 'Glock | Gamma Doppler Phase 4';
                elseif (strpos($filename, 'phase3') !== false) $info['name'] = 'Glock | Gamma Doppler Phase 3';
                elseif (strpos($filename, 'phase1') !== false) $info['name'] = 'Glock | Gamma Doppler Phase 1';
                else $info['name'] = 'Glock | Gamma Doppler';
            } elseif (strpos($filename, 'wasteland_rebel') !== false) {
                $info['name'] = 'Glock | Wasteland Rebel';
            } else {
                $info['name'] = 'Glock';
            }
        } elseif (strpos($filename, 'famas') !== false) {
            $info['weapon_type'] = 'rifle';
            $info['name'] = 'FAMAS | Rally';
        } elseif (strpos($filename, 'mac10') !== false) {
            $info['weapon_type'] = 'smg';
            $info['name'] = 'MAC-10 | Neon Rider';
        } elseif (strpos($filename, 'galil') !== false) {
            $info['weapon_type'] = 'rifle';
            $info['name'] = 'Galil AR | Abrasion';
        } elseif (strpos($filename, 'scar20') !== false) {
            $info['weapon_type'] = 'sniper';
            $info['name'] = 'SCAR-20 | Intervention';
        } elseif (strpos($filename, 'mp9') !== false) {
            $info['weapon_type'] = 'smg';
            $info['name'] = 'MP9 | Narcis';
        } elseif (strpos($filename, 'sg556') !== false || strpos($filename, 'sg553') !== false) {
            $info['weapon_type'] = 'rifle';
            $info['name'] = 'SG 556 | Reactor';
        }
    }
    // Classified (Pink) - 5%
    elseif (strpos($filename, 'bloom') !== false || strpos($filename, 'jorm') !== false ||
            strpos($filename, 'chronos') !== false || strpos($filename, 'mag7_glass') !== false ||
            strpos($filename, 'flowers') !== false || strpos($filename, 'seastorm_shojo') !== false ||
            strpos($filename, 'lightning') !== false || strpos($filename, 'kimono_diamonds') !== false) {
        $info['rarity'] = 3; // Classified
        $info['price'] = rand(70, 90);
        
        if (strpos($filename, 'aug') !== false) {
            $info['weapon_type'] = 'rifle';
            if (strpos($filename, 'bloom') !== false) $info['name'] = 'AUG | Bloom Blue';
            elseif (strpos($filename, 'jorm') !== false) $info['name'] = 'AUG | Jorm Orange';
            else $info['name'] = 'AUG';
        } elseif (strpos($filename, 'p90') !== false) {
            $info['weapon_type'] = 'smg';
            $info['name'] = 'P90 | Jorm Blue';
        } elseif (strpos($filename, 'g3sg1') !== false) {
            $info['weapon_type'] = 'sniper';
            $info['name'] = 'G3SG1 | Chronos';
        } elseif (strpos($filename, 'mag7') !== false) {
            $info['weapon_type'] = 'shotgun';
            $info['name'] = 'MAG-7 | Glass';
        } elseif (strpos($filename, 'ssg08') !== false) {
            $info['weapon_type'] = 'sniper';
            $info['name'] = 'SSG 08 | Flowers';
        } elseif (strpos($filename, 'deagle') !== false) {
            $info['weapon_type'] = 'pistol';
            if (strpos($filename, 'seastorm_shojo') !== false) $info['name'] = 'Desert Eagle | Seastorm Shojo';
            elseif (strpos($filename, 'jorm_green') !== false) $info['name'] = 'Desert Eagle | Jorm Green';
            else $info['name'] = 'Desert Eagle';
        } elseif (strpos($filename, 'fiveseven') !== false) {
            $info['weapon_type'] = 'pistol';
            $info['name'] = 'Five-SeveN | Kimono Diamonds';
        } elseif (strpos($filename, 'awp') !== false) {
            $info['weapon_type'] = 'sniper';
            $info['name'] = 'AWP | Lightning';
        }
    }
    // Restricted (Purple) - 15%
    elseif (strpos($filename, 'hunter') !== false || strpos($filename, 'chainmail') !== false ||
            strpos($filename, 'phoenix') !== false || strpos($filename, 'green_leather') !== false ||
            strpos($filename, 'leather') !== false || strpos($filename, 'knots') !== false ||
            strpos($filename, 'octopump') !== false || strpos($filename, 'veneto') !== false ||
            strpos($filename, 'copper') !== false || strpos($filename, 'crystallized') !== false ||
            strpos($filename, 'bud_red') !== false || strpos($filename, 'vertigo') !== false ||
            strpos($filename, 'royal') !== false || strpos($filename, 'intelligence') !== false ||
            strpos($filename, 'scales') !== false || strpos($filename, 'handcannon') !== false ||
            strpos($filename, 'aqua_flecks') !== false || strpos($filename, 'mother_of_pearl') !== false ||
            strpos($filename, 'leaf_blue') !== false || strpos($filename, 'crumple') !== false ||
            strpos($filename, 'tigers') !== false || strpos($filename, 'yellow') !== false ||
            strpos($filename, 'oiled') !== false || strpos($filename, 'crime_scene') !== false ||
            strpos($filename, 'bud_green') !== false || strpos($filename, 'stained_glass') !== false ||
            strpos($filename, 'labyrinth') !== false || strpos($filename, 'pandora') !== false ||
            strpos($filename, 'snake') !== false || strpos($filename, 'an_red') !== false ||
            strpos($filename, 'navy') !== false || strpos($filename, 'an_red') !== false) {
        $info['rarity'] = 4; // Restricted
        $info['price'] = rand(40, 60);
        
        // Określ typ broni na podstawie nazwy pliku
        if (strpos($filename, 'nova') !== false) {
            $info['weapon_type'] = 'shotgun';
            if (strpos($filename, 'hunter') !== false) $info['name'] = 'Nova | Hunter Blaze Orange';
            elseif (strpos($filename, 'veneto') !== false) $info['name'] = 'Nova | Veneto';
            else $info['name'] = 'Nova';
        } elseif (strpos($filename, 'mag7') !== false) {
            $info['weapon_type'] = 'shotgun';
            $info['name'] = 'MAG-7 | Chainmail';
        } elseif (strpos($filename, 'negev') !== false) {
            $info['weapon_type'] = 'lmg';
            if (strpos($filename, 'phoenix') !== false) $info['name'] = 'Negev | Phoenix Tags Red';
            elseif (strpos($filename, 'navy') !== false) $info['name'] = 'Negev | Navy';
            else $info['name'] = 'Negev';
        } elseif (strpos($filename, 'sawedoff') !== false) {
            $info['weapon_type'] = 'shotgun';
            if (strpos($filename, 'green_leather') !== false) $info['name'] = 'Sawed-Off | Green Leather';
            elseif (strpos($filename, 'octopump') !== false) $info['name'] = 'Sawed-Off | Octopump';
            elseif (strpos($filename, 'copper') !== false) $info['name'] = 'Sawed-Off | Copper';
            else $info['name'] = 'Sawed-Off';
        } elseif (strpos($filename, 'xm1014') !== false) {
            $info['weapon_type'] = 'shotgun';
            if (strpos($filename, 'leather') !== false) $info['name'] = 'XM1014 | Leather';
            elseif (strpos($filename, 'knots') !== false) $info['name'] = 'XM1014 | Knots Silver';
            else $info['name'] = 'XM1014';
        } elseif (strpos($filename, 'cz75') !== false) {
            $info['weapon_type'] = 'pistol';
            if (strpos($filename, 'crystallized') !== false) $info['name'] = 'CZ75-Auto | Crystallized Green';
            elseif (strpos($filename, 'royal') !== false) $info['name'] = 'CZ75-Auto | Royal';
            else $info['name'] = 'CZ75-Auto';
        } elseif (strpos($filename, 'fiveseven') !== false) {
            $info['weapon_type'] = 'pistol';
            $info['name'] = 'Five-SeveN | Bud Red';
        } elseif (strpos($filename, 'deagle') !== false) {
            $info['weapon_type'] = 'pistol';
            if (strpos($filename, 'vertigo') !== false) $info['name'] = 'Desert Eagle | Vertigo';
            elseif (strpos($filename, 'scales') !== false) $info['name'] = 'Desert Eagle | Scales Bravo';
            elseif (strpos($filename, 'handcannon') !== false) $info['name'] = 'Desert Eagle | Handcannon';
            else $info['name'] = 'Desert Eagle';
        } elseif (strpos($filename, 'usp') !== false) {
            $info['weapon_type'] = 'pistol';
            $info['name'] = 'USP-S | Intelligence Magenta';
        } elseif (strpos($filename, 'glock') !== false) {
            $info['weapon_type'] = 'pistol';
            if (strpos($filename, 'aqua_flecks') !== false) $info['name'] = 'Glock | Aqua Flecks';
            elseif (strpos($filename, 'leaf_blue') !== false) $info['name'] = 'Glock | Leaf Blue';
            else $info['name'] = 'Glock';
        } elseif (strpos($filename, 'elite') !== false) {
            $info['weapon_type'] = 'pistol';
            $info['name'] = 'Dual Berettas | Mother of Pearl';
        } elseif (strpos($filename, 'awp') !== false) {
            $info['weapon_type'] = 'sniper';
            if (strpos($filename, 'crumple') !== false) $info['name'] = 'AWP | Crumple Bravo';
            elseif (strpos($filename, 'tigers') !== false) $info['name'] = 'AWP | Tigers Blue';
            else $info['name'] = 'AWP';
        } elseif (strpos($filename, 'sg556') !== false) {
            $info['weapon_type'] = 'rifle';
            $info['name'] = 'SG 556 | Yellow';
        } elseif (strpos($filename, 'ak47') !== false) {
            $info['weapon_type'] = 'rifle';
            $info['name'] = 'AK-47 | Oiled';
        } elseif (strpos($filename, 'ump45') !== false) {
            $info['weapon_type'] = 'smg';
            if (strpos($filename, 'crime_scene') !== false) $info['name'] = 'UMP-45 | Crime Scene';
            elseif (strpos($filename, 'labyrinth') !== false) $info['name'] = 'UMP-45 | Labyrinth';
            else $info['name'] = 'UMP-45';
        } elseif (strpos($filename, 'mp7') !== false) {
            $info['weapon_type'] = 'smg';
            $info['name'] = 'MP7 | Bud Green';
        } elseif (strpos($filename, 'mp9') !== false) {
            $info['weapon_type'] = 'smg';
            if (strpos($filename, 'stained_glass') !== false) $info['name'] = 'MP9 | Stained Glass';
            elseif (strpos($filename, 'yellow') !== false) $info['name'] = 'MP9 | Yellow';
            elseif (strpos($filename, 'pandora') !== false) $info['name'] = 'MP9 | Pandora';
            elseif (strpos($filename, 'an_red') !== false) $info['name'] = 'MP9 | Red';
            else $info['name'] = 'MP9';
        } elseif (strpos($filename, 'mac10') !== false) {
            $info['weapon_type'] = 'smg';
            if (strpos($filename, 'knots') !== false) $info['name'] = 'MAC-10 | Knots Brown';
            elseif (strpos($filename, 'snake') !== false) $info['name'] = 'MAC-10 | Snake';
            else $info['name'] = 'MAC-10';
        } elseif (strpos($filename, 'aug') !== false) {
            $info['weapon_type'] = 'rifle';
            $info['name'] = 'AUG | Red';
        }
    }
    // Mil-Spec (Blue) - 35%
    elseif (strpos($filename, 'fade') !== false) {
        $info['rarity'] = 5; // Mil-Spec
        $info['price'] = rand(25, 30);
        
        if (strpos($filename, 'm4a1') !== false) {
            $info['weapon_type'] = 'rifle';
            $info['name'] = 'M4A1-S | Fade';
        } elseif (strpos($filename, 'awp') !== false) {
            $info['weapon_type'] = 'sniper';
            $info['name'] = 'AWP | Fade';
        }
    }
    // Consumer Grade (Light Blue) - 42.9% - wszystkie pozostałe
    else {
        $info['rarity'] = 6; // Consumer Grade
        $info['price'] = rand(5, 20);
        
        // Określ typ broni na podstawie nazwy pliku
        if (strpos($filename, 'ak47') !== false || strpos($filename, 'm4a1') !== false || 
            strpos($filename, 'aug') !== false || strpos($filename, 'famas') !== false ||
            strpos($filename, 'galil') !== false || strpos($filename, 'sg556') !== false) {
            $info['weapon_type'] = 'rifle';
        } elseif (strpos($filename, 'awp') !== false || strpos($filename, 'ssg08') !== false ||
                  strpos($filename, 'g3sg1') !== false || strpos($filename, 'scar20') !== false) {
            $info['weapon_type'] = 'sniper';
        } elseif (strpos($filename, 'deagle') !== false || strpos($filename, 'glock') !== false ||
                  strpos($filename, 'usp') !== false || strpos($filename, 'p250') !== false ||
                  strpos($filename, 'cz75') !== false || strpos($filename, 'fiveseven') !== false ||
                  strpos($filename, 'elite') !== false) {
            $info['weapon_type'] = 'pistol';
        } elseif (strpos($filename, 'p90') !== false || strpos($filename, 'ump45') !== false ||
                  strpos($filename, 'mp7') !== false || strpos($filename, 'mp9') !== false ||
                  strpos($filename, 'mac10') !== false) {
            $info['weapon_type'] = 'smg';
        } elseif (strpos($filename, 'nova') !== false || strpos($filename, 'mag7') !== false ||
                  strpos($filename, 'sawedoff') !== false || strpos($filename, 'xm1014') !== false) {
            $info['weapon_type'] = 'shotgun';
        } elseif (strpos($filename, 'negev') !== false) {
            $info['weapon_type'] = 'lmg';
        }
        
        // Generuj nazwę na podstawie nazwy pliku
        $parts = explode('_', $filename);
        $weaponName = '';
        foreach ($parts as $part) {
            if (strpos($part, 'weapon') === false && strpos($part, 'light') === false && 
                strpos($part, 'png') === false && strpos($part, 'medium') === false) {
                $weaponName .= ucfirst($part) . ' ';
            }
        }
        $info['name'] = trim($weaponName) ?: 'Weapon';
    }
    
    return $info;
}

try {
    // Sprawdź czy folder istnieje
    if (!is_dir($imagesPath)) {
        throw new Exception("Folder nie istnieje: $imagesPath\nUpewnij się, że rozpakowałeś pliki z skiny.rar do tego folderu.");
    }
    
    // Skanuj folder z obrazami
    $files = scandir($imagesPath);
    $pngFiles = array_filter($files, function($file) {
        return pathinfo($file, PATHINFO_EXTENSION) === 'png';
    });
    
    if (empty($pngFiles)) {
        throw new Exception("Nie znaleziono plików PNG w folderze: $imagesPath");
    }
    
    echo "Znaleziono " . count($pngFiles) . " plików PNG.\n";
    echo "Rozpoczynam import...\n\n";
    
    $pdo->beginTransaction();
    
    // Usuń stare dane (opcjonalnie - odkomentuj jeśli chcesz)
    // $pdo->exec("DELETE FROM case_items");
    // $pdo->exec("DELETE FROM user_items");
    // $pdo->exec("DELETE FROM items");
    
    $inserted = 0;
    $skipped = 0;
    $items = [];
    
    foreach ($pngFiles as $filename) {
        $info = parseFileName($filename);
        
        // Sprawdź czy przedmiot już istnieje
        $checkStmt = $pdo->prepare("SELECT id FROM items WHERE image_path = ?");
        $checkStmt->execute([$filename]);
        if ($checkStmt->fetch()) {
            $skipped++;
            continue;
        }
        
        // Jeśli nie ma nazwy, użyj nazwy pliku
        if (empty($info['name'])) {
            $info['name'] = str_replace(['_', '.png'], [' ', ''], $filename);
            $info['name'] = ucwords($info['name']);
        }
        
        // Wstaw przedmiot do bazy danych
        $stmt = $pdo->prepare("INSERT INTO items (name, weapon_type, image_path, rarity_id, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $info['name'],
            $info['weapon_type'],
            $imageUrlPath . $filename, // Pełna ścieżka URL
            $info['rarity'],
            $info['price']
        ]);
        
        $items[] = [
            'id' => $pdo->lastInsertId(),
            'rarity_id' => $info['rarity']
        ];
        
        $inserted++;
        echo "✓ Dodano: {$info['name']} ({$info['weapon_type']}, Rarity: {$info['rarity']}, Price: {$info['price']})\n";
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
    
    // Usuń stare powiązania case_items dla tej skrzynki
    $pdo->exec("DELETE FROM case_items WHERE case_id = $caseId");
    
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
    
    echo "\n========================================\n";
    echo "SUKCES!\n";
    echo "Zaimportowano: $inserted przedmiotów\n";
    if ($skipped > 0) {
        echo "Pominięto (już istnieją): $skipped przedmiotów\n";
    }
    echo "Wszystkie przedmioty zostały dodane do skrzynki #$caseId z odpowiednimi szansami.\n";
    echo "Ścieżka obrazów: $imageUrlPath\n";
    echo "========================================\n";
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo "BŁĄD: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

