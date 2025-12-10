# Instrukcja importu przedmiotów do bazy danych

## Krok 1: Rozpakuj pliki PNG

1. Rozpakuj archiwum `skiny.rar` do folderu:
   ```
   C:\xampp\htdocs\glimzyskins\skiny\
   ```

2. Upewnij się, że wszystkie pliki PNG są w tym folderze (nie w podfolderach).

## Krok 2: Uruchom skrypt importu

### Opcja A: Przez przeglądarkę
Otwórz w przeglądarce:
```
http://localhost/glimzyskins/import_items_auto.php
```

### Opcja B: Z linii poleceń
```bash
cd C:\xampp\htdocs\glimzyskins
php import_items_auto.php
```

## Krok 3: Sprawdź wyniki

Skrypt automatycznie:
- ✓ Skanuje folder z obrazami PNG
- ✓ Parsuje nazwy plików i wyodrębnia informacje o przedmiotach
- ✓ Przypisuje rzadkości na podstawie typu przedmiotu:
  - **Exceedingly Rare (0.1%)**: Noże, Rękawice, Zeus
  - **Covert (2%)**: Najlepsze skiny broni
  - **Classified (5%)**: Bardzo dobre skiny
  - **Restricted (15%)**: Dobre skiny
  - **Mil-Spec (35%)**: Średnie skiny
  - **Consumer Grade (42.9%)**: Podstawowe skiny
- ✓ Ustawia ceny na podstawie rzadkości
- ✓ Dodaje wszystkie przedmioty do skrzynki #1 z odpowiednimi szansami

## Struktura ścieżek

- **Lokalna ścieżka**: `C:\xampp\htdocs\glimzyskins\skiny\`
- **URL dla aplikacji**: `http://10.0.2.2/glimzyskins/skiny/`

## Rozwiązywanie problemów

### Błąd: "Folder nie istnieje"
- Upewnij się, że folder `C:\xampp\htdocs\glimzyskins\skiny\` istnieje
- Rozpakuj pliki z `skiny.rar` do tego folderu

### Błąd: "Nie znaleziono plików PNG"
- Sprawdź, czy pliki PNG są w folderze (nie w podfolderach)
- Sprawdź rozszerzenia plików (muszą być `.png`)

### Przedmioty nie wyświetlają się w aplikacji
- Sprawdź, czy ścieżka URL jest poprawna w bazie danych
- Sprawdź, czy serwer Apache działa
- Sprawdź, czy pliki są dostępne przez HTTP

## Uwagi

- Skrypt automatycznie pomija duplikaty (sprawdza `image_path`)
- Jeśli chcesz zaimportować ponownie, odkomentuj linie usuwające stare dane w skrypcie
- Wszystkie przedmioty są dodawane do skrzynki #1 (`Glimzy Case #1`)

