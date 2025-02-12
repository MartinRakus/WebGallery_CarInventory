
# Car and Part Management System
Webová aplikácia na správu vozidiel a dielov, vytvorená pomocou Laravelu na backende a Vue.js na fronte. Na dizajn je použitý Bootstrap a ako databáza sa využíva MySQL.

## 📚 Popis projektu
Aplikácia umožňuje:
- Zobrazenie zoznamu vozidiel a dielov
- Pridávanie nových vozidiel a dielov
- Úpravu existujúcich vozidiel a dielov
- Mazanie vozidiel a dielov
- Filtrovanie zoznamu vozidiel a dielov

### 📊 Štruktúra databázy
1.  **Car** (Vozidlo)
-  `id` - primárny kľúč
-  `name` - názov vozidla (povinné)
-  `registration_number` - registračné číslo (povinné len ak je vozidlo registrované) - unikátny kód
-  `is_registered` - boolean, či je vozidlo registrované
-  `created_at`, `updated_at` - časové pečiatky

###
2.  **Part** (Diel)
-  `id` - primárny kľúč
-  `name` - názov dielu (povinné)
-  `serialnumber` - sériové číslo (povinné) - unikátny kód
-  `car_id` - cudzí kľúč na tabuľku `Car` (každý diel patrí len k jednému vozidlu)
-  `created_at`, `updated_at` - časové pečiatky

## ⚙️ Použité technológie
-  **Backend:** Laravel
-  **Frontend:** Vue.js
-  **Dizajn:** Bootstrap
-  **Databáza:** MySQL

## 🚀 Inštalácia a spustenie projektu
1. Naklonujte repozitár:
```sh
git clone <URL REPOZITÁRA>

cd WebGallery_CarInventory
```

2. Nainštalujte závislosti pomocou Composer a NPM:
```sh
composer install

npm install

npm run dev
```

3. Skopírujte súbor `.env.example` na `.env` a nastavte si údaje k databáze:
```sh
cp .env.example .env
```

4. Vygenerujte aplikáčny kľúč:
```sh
php artisan key:generate
```

5. Vykonajte migrácie:
```sh
php artisan migrate
```

6. Spustite lokálny server:
```sh
php artisan serve
```  

## 🔑 Funkcionality
-  **CRUD operácie**:
- Vytváranie, čítanie, úprava a mazanie vozidiel a dielov

###
-  **Validácia formulárov**:
-  `name`, `serialnumber` sú povinné
-  `registration_number` je povinné len ak `is_registered` je `true`
-  `serialnumber` a `registration_number` nesmú byť duplicitné (doplnkové obmedzenie)

###
-  **Väzby medzi modelmi**:
- Jedno vozidlo môže mať viacero dielov
- Jeden diel patrí len k jednému vozidlu

###
-  **Filtrovanie**:
- Možnosť filtrovať zoznam vozidiel a dielov podľa textu (názov, sériové číslo, registračné číslo)
- Možnosť filtrovať zoznam dielov podľa výberu vozidiel v tabuľke vozidiel