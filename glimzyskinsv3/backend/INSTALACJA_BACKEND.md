# Instrukcja instalacji backendu PHP

## Krok 1: Skopiuj pliki
Skopiuj wszystkie pliki z folderu `backend/` do:
```
C:/xampp/htdocs/glimzyskins/
```

Powinny być tam następujące pliki:
- `db.php` - połączenie z bazą danych
- `login.php` - endpoint logowania
- `register.php` - endpoint rejestracji
- `.htaccess` - konfiguracja Apache (CORS, itp.)
- `test.php` - plik testowy do sprawdzenia działania (opcjonalny)

## Krok 2: Utwórz bazę danych

### Opcja A: Przez phpMyAdmin
1. Uruchom XAMPP
2. Włącz Apache i MySQL
3. Otwórz http://localhost/phpmyadmin
4. Kliknij "Nowa" (New) w lewym menu
5. Wpisz nazwę bazy: `glimzyskins`
6. Wybierz kodowanie: `utf8mb4_general_ci`
7. Kliknij "Utwórz"

### Opcja B: Przez SQL
1. W phpMyAdmin kliknij zakładkę "SQL"
2. Wklej zawartość pliku `database_schema.sql`
3. Kliknij "Wykonaj"

## Krok 3: Sprawdź konfigurację

Otwórz plik `C:/xampp/htdocs/glimzyskins/db.php` i sprawdź:
- host: `localhost` ✓
- user: `root` ✓
- password: (puste) ✓
- database: `glimzyskins` ✓

Jeśli masz inne hasło do MySQL, zmień je w pliku `db.php`.

## Krok 4: Test

### Test przez przeglądarkę (najłatwiejszy sposób):
1. Otwórz: **http://localhost/glimzyskins/test.php**
2. Użyj interfejsu testowego do sprawdzenia wszystkich endpointów
3. Możesz przetestować rejestrację i logowanie bezpośrednio w przeglądarce

### Test przez Postman/curl:
```bash
# Test rejestracji
curl -X POST http://localhost/glimzyskins/register.php \
  -d "email=test@test.com&password=test123"

# Test logowania
curl -X POST http://localhost/glimzyskins/login.php \
  -d "email=test@test.com&password=test123"
```

Powinieneś otrzymać odpowiedź JSON:
```json
{
  "status": true,
  "message": "Rejestracja zakończona pomyślnie"
}
```

## Rozwiązywanie problemów

### Błąd: "Błąd połączenia z bazą danych"
- Sprawdź czy MySQL jest uruchomiony w XAMPP
- Sprawdź czy baza danych `glimzyskins` istnieje
- Sprawdź dane logowania w `db.php`

### Błąd: "Access denied"
- Sprawdź czy użytkownik `root` ma dostęp do bazy danych
- W phpMyAdmin sprawdź uprawnienia użytkownika

### Błąd 404: "Nie znaleziono"
- Sprawdź czy pliki są w `C:/xampp/htdocs/glimzyskins/`
- Sprawdź czy Apache jest uruchomiony
- Sprawdź czy folder nazywa się dokładnie `glimzyskins`

## Gotowe! 🎉

Backend jest gotowy do użycia. Aplikacja Android będzie łączyć się z:
- http://10.0.2.2/glimzyskins/ (dla emulatora)
- http://[IP_KOMPUTERA]/glimzyskins/ (dla urządzenia fizycznego)

