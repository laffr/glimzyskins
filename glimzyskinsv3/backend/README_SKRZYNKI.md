# System Skrzynek - Instrukcja

## Instalacja

### 1. Rozszerz bazę danych
Wykonaj plik SQL w phpMyAdmin:
```
backend/database_schema_extended.sql
```

To doda:
- Kolumnę `balance` do tabeli `users` (domyślnie 1000.00)
- Tabele: `rarity`, `items`, `cases`, `case_items`, `user_items`, `transactions`

### 2. Zaimportuj skiny
Uruchom skrypt PHP (z linii poleceń lub przez przeglądarkę):
```
php import_items.php
```

Lub otwórz w przeglądarce:
```
http://localhost/glimzyskins/import_items.php
```

Skrypt:
- Doda wszystkie skiny z folderu do bazy danych
- Przypisze odpowiednie rzadkości
- Doda wszystkie przedmioty do pierwszej skrzynki z odpowiednimi szansami

### 3. Skopiuj pliki PNG
Skopiuj wszystkie pliki PNG z folderu `skiny.rar` do:
```
C:/xampp/htdocs/glimzyskins/images/
```

## Endpointy API

### 1. Pobierz listę skrzynek
```
GET /get_cases.php
```
Zwraca listę aktywnych skrzynek.

### 2. Otwórz skrzynkę
```
POST /open_case.php
Parametry:
- user_id (int)
- case_id (int)
```
Otwiera skrzynkę i losuje przedmiot. Odejmuje saldo użytkownika.

### 3. Pobierz przedmioty użytkownika
```
GET /get_user_items.php?user_id=1
```
Zwraca wszystkie przedmioty w kolekcji użytkownika.

### 4. Sprzedaj przedmiot
```
POST /sell_item.php
Parametry:
- user_id (int)
- user_item_id (int)
```
Sprzedaje przedmiot za 70% wartości. Dodaje saldo użytkownika.

### 5. Pobierz saldo
```
GET /get_balance.php?user_id=1
```
Zwraca saldo użytkownika.

### 6. Pobierz przedmioty w skrzynce
```
GET /get_case_items.php?case_id=1
```
Zwraca wszystkie przedmioty dostępne w skrzynce z szansami.

## Rzadkość (Rarity)

1. **Exceedingly Rare** (Złoty) - 0.10%
   - Noże, Rękawice, Zeus
   - Najrzadsze przedmioty

2. **Covert** (Czerwony) - 2.00%
   - Najlepsze skiny broni

3. **Classified** (Różowy) - 5.00%
   - Bardzo dobre skiny

4. **Restricted** (Fioletowy) - 15.00%
   - Dobre skiny

5. **Mil-Spec** (Niebieski) - 35.00%
   - Średnie skiny

6. **Consumer Grade** (Jasnoniebieski) - 42.90%
   - Podstawowe skiny

## System losowania

System używa kumulatywnego losowania:
- Każdy przedmiot ma przypisaną szansę w procentach
- System losuje liczbę 0.01-100.00
- Wybiera pierwszy przedmiot, którego kumulatywna szansa >= wylosowana liczba

## Saldo użytkownika

- Nowi użytkownicy otrzymują **1000.00** przy rejestracji
- Otwarcie skrzynki kosztuje **2.50**
- Sprzedaż przedmiotu daje **70%** wartości przedmiotu

## Przykładowe użycie

### Otwarcie skrzynki:
```bash
curl -X POST http://localhost/glimzyskins/open_case.php \
  -d "user_id=1&case_id=1"
```

Odpowiedź:
```json
{
  "status": true,
  "item": {
    "id": 1,
    "name": "AK-47 | Fire Serpent",
    "weapon_type": "rifle",
    "image_path": "weapon_ak47_cu_fireserpent_ak47_bravo_light_png.png",
    "rarity": {
      "id": 2,
      "name": "Covert",
      "color": "#EB4B4B"
    },
    "price": 150.00
  },
  "new_balance": 997.50,
  "user_item_id": 1
}
```

## Uwagi

- Wszystkie pliki PNG muszą być dostępne w folderze `images/`
- Upewnij się, że ścieżki do obrazów są poprawne
- System automatycznie zarządza saldem użytkownika
- Wszystkie transakcje są logowane w tabeli `transactions`

