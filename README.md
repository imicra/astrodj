# Персональная тема основанная на _s (A Starter Theme for WordPress)

См.
- [github](https://github.com/automattic/_s)
- [_s](https://underscores.me/)

Фичи (Features)
---------------
Ajax load more, ajax pagination, ajax filter, lqip for images (low-quality image placeholders).
Display EXIF data for images.

Описание
---------------

Компилляция идет с помощью отдельной 'темы' `gulp-dev`:
- [См. репозиторий](https://github.com/imicra/gulp-dev)

### Компилляция

* В папке `sass` вся работа со стилями
* В папке `js-dev` главный файл - `main.js`
* В папке `js-dev` файлы с суфиксом `inc` минифицируются в отдельные файлы, которые подключаются на нужных страницх
* Все `js` файлы из папки `js-dev` собираются в папку `js`, которая и используется сайтом
* В папку `src` скидываются все плагины jQuery/js и т.п. А также в папке `img` находится `svg-icons.svg` для всех иконок, которые используются отдельно, как картинки svg. В `sass` лежат bootstrap 4 и fontawesome.
* Из `src` файлы компилируется в папки `css` и `js` под названием `libs.min.css` и `libs.min.js`, соответственно

### Файлы

* Вся настройка в functions.php. Там же подключаются файлы из папки `inc`.

### inc
Подключаемые файлы с разным функционалом.

* Папка с плагином `CMB2`. И файл `cmb2.php` с метабоксами. Описание плагина см. [CMB2](https://github.com/CMB2/CMB2/wiki/Field-Types/).
* Файл `custom-styles.php` создает css в head, управляемые в Customizer.
* Файл `icon-functions.php` для вставки svg в код и создания меню.
* Файл `custom-walker-nav-menu.php` для создания нового Walker_Nav_Menu (не используется)
* Папка `theme-functions.php` с кастомными функциями.
* Папка `cpt` для Custom Post Types.
* Папка `widgets` с виджетами.
* Папка `admin` для расширения функционала админки.
* Файл `woocommerce.php` с начальными настройками для подключения в тему Woocommerce. На случай, если вдруг когда-нибудь понадобится.
