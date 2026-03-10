(function () {
    console.log("iGoShop!");

    // Настройки
    var SHOP_URL = "https://igoevent.com/js/micro/igoshop/v1/igoshop"; // "http://igoshop.baraban.io/igoshop/";  // "/igoshop"; 
    var CART_KEY = "shop.cart";
    var FAV_KEY = "shop.fav";
    // var INTENT_KEY = "shop.intent"; // чтобы /shop понял, что открыть (cart/fav)

    // Добавляем стили для UI элементов
    function addStyles() {
        var style = document.createElement("style");
        style.textContent = `
            .igoshop-quantity-controls {
                display: inline-flex;
                align-items: center;
                margin-right: 0.5rem;
                vertical-align: middle;
            }
            .igoshop-quantity-btn {
                width: 1.5rem;
                height: 1.5rem;
                font-size: 1rem;
                line-height: 1;
                padding: 0;
                border: 1px solid #ccc;
                background: #f0f0f0;
                cursor: pointer;
            }
            .igoshop-quantity-value {
                width: 2rem;
                text-align: center;
                margin: 0 0.25rem;
                display: inline-block;
            }
            .igoshop-notification {
                padding: 0.75rem 1rem;
                background-color: #4CAF50;
                color: white;
                border-radius: 4px;
                margin: 0.5rem 0;
                animation: fadeOut 3s forwards;
                animation-delay: 2s;
            }
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; visibility: hidden; }
            }
        `;
        document.head.appendChild(style);
    }

    function read(key, fallback) {
        try {
            return JSON.parse(localStorage.getItem(key) || JSON.stringify(fallback));
        } catch (e) {
            return fallback;
        }
    }

    function write(key, val) {
        localStorage.setItem(key, JSON.stringify(val));
    }

    function toNumber(x, def) {
        if (x == null) return def;
        var n = parseFloat(
            String(x)
                .replace(/\s+/g, "") // убираем пробелы "22 000" -> "22000"
                .replace(",", ".") // запятую в точку "22000,5" -> "22000.5"
        );
        return isFinite(n) ? n : def;
    }

    function getItemFromNode(node) {
        console.log("getItemFromNode", node);
        return {
            authorid: node.dataset.authorid || "",
            author: node.dataset.author || "",
            sku: node.dataset.sku || "",
            name: node.dataset.name || "",
            url: node.dataset.url || location.href,
            image: node.dataset.image || "",
            price: toNumber(node.dataset.price, 0),
            count: getQuantityValue(node) || 1,
            amount: 0, // посчитаем ниже
            currency: (node.dataset.currency || "RUB").toUpperCase(),
        };
    }

    function getQuantityValue(node) {
        var quantityElement = node.querySelector(".igoshop-quantity-value");
        return quantityElement ? parseInt(quantityElement.textContent, 10) : 1;
    }

    function addToCart(item) {
        var cart = read(CART_KEY, []);
        var idx = cart.findIndex(function (x) {
            return x.sku === item.sku;
        });
        if (idx >= 0) {
            cart[idx].count = (parseInt(cart[idx].count, 10) || 0) + (parseInt(item.count, 10) || 0);
            cart[idx].amount = cart[idx].count * toNumber(cart[idx].price, 0);
        } else {
            item.amount = toNumber(item.count, 1) * toNumber(item.price, 0);
            cart.push(item);
        }
        console.log("addToCart", cart);

        write(CART_KEY, cart);
        // write(INTENT_KEY, { open: "cart", ts: Date.now() }); // чтобы /shop понял, что открыть (cart/fav)
        // window.location.href = SHOP_URL;
        updateGoToCartButton();

        // Показываем уведомление о том, что товар был добавлен в корзину
        showAddedToCartNotification(item);
    }

    function showAddedToCartNotification(item) {
        // Находим все карточки товаров с этим SKU
        var cards = document.querySelectorAll('[data-sku="' + item.sku + '"]');

        cards.forEach(function (card) {
            var actionsDiv = card.querySelector(".igoshop-actions");
            if (!actionsDiv) return;

            // Удаляем все существующие уведомления
            var existingNotifications = actionsDiv.querySelectorAll(".igoshop-notification");
            existingNotifications.forEach(function (notification) {
                notification.remove();
            });

            // Создаем и добавляем новое уведомление
            var notification = document.createElement("div");
            notification.className = "igoshop-notification";
            notification.textContent = "Товар " + item.name + " добавлен в корзину";
            actionsDiv.appendChild(notification);

            // Удаляем уведомление через 5 секунд
            setTimeout(() => notification.remove(), 5000);
        });
    }

    function createQuantityControls() {
        var addButtons = document.querySelectorAll(".igoshop-btn-add");

        addButtons.forEach(function (btn) {
            // Проверяем, если уже есть элементы для управления количеством
            if (btn.previousElementSibling && btn.previousElementSibling.classList.contains("igoshop-quantity-controls")) {
                return;
            }

            var controlsDiv = document.createElement("div");
            controlsDiv.className = "igoshop-quantity-controls";

            var minusBtn = document.createElement("button");
            minusBtn.className = "igoshop-quantity-btn";
            minusBtn.textContent = "-";
            minusBtn.onclick = function (e) {
                e.preventDefault();
                e.stopPropagation();
                var valueSpan = this.nextElementSibling;
                var value = parseInt(valueSpan.textContent, 10);
                if (value > 1) {
                    valueSpan.textContent = value - 1;
                }
            };

            var valueSpan = document.createElement("span");
            valueSpan.className = "igoshop-quantity-value";
            valueSpan.textContent = "1";

            var plusBtn = document.createElement("button");
            plusBtn.className = "igoshop-quantity-btn";
            plusBtn.textContent = "+";
            plusBtn.onclick = function (e) {
                e.preventDefault();
                e.stopPropagation();
                var valueSpan = this.previousElementSibling;
                var value = parseInt(valueSpan.textContent, 10);
                valueSpan.textContent = value + 1;
            };

            controlsDiv.appendChild(minusBtn);
            controlsDiv.appendChild(valueSpan);
            controlsDiv.appendChild(plusBtn);

            // Вставляем перед кнопкой добавления в корзину
            btn.parentNode.insertBefore(controlsDiv, btn);
        });
    }

    function addToFav(item) {
        console.log("addToFav", item);
        var fav = read(FAV_KEY, []);
        if (
            !fav.some(function (x) {
                return x.sku === item.sku;
            })
        ) {
            fav.push({ sku: item.sku, name: item.name, url: item.url, image: item.image });
            write(FAV_KEY, fav);
        }
        // write(INTENT_KEY, { open: "fav", ts: Date.now() });
        // window.location.href = SHOP_URL;
    }

    function hasCartItems() {
        var cart = read(CART_KEY, []);
        return cart.length > 0;
    }

    function updateGoToCartButton() {
        var actions = document.querySelectorAll(".igoshop-actions");
        actions.forEach(function (actionsDiv) {
            var existingBtn = actionsDiv.querySelector(".igoshop-btn-gotocart");

            if (hasCartItems()) {
                if (!existingBtn) {
                    var goToCartBtn = document.createElement("button");
                    goToCartBtn.type = "button";
                    goToCartBtn.className = "igoshop-btn-gotocart";
                    goToCartBtn.style.padding = "0.5rem 1.5rem";
                    goToCartBtn.style.marginTop = "0.5rem";
                    goToCartBtn.textContent = "Перейти в корзину";
                    goToCartBtn.addEventListener("click", function () {
                        window.location.href = SHOP_URL;
                    });
                    actionsDiv.appendChild(goToCartBtn);
                }
            } else if (existingBtn) {
                existingBtn.remove();
            }
        });
    }

    // Делегирование кликов: работает и для каталога, и для карточки
    document.addEventListener("click", function (e) {
        var addCartBtn = e.target.closest(".igoshop-btn-add");
        if (addCartBtn) {
            var card = addCartBtn.closest("[data-sku]");
            if (!card) return;
            e.preventDefault();
            var item = getItemFromNode(card);
            console.log("item", item);
            addToCart(item);
            return;
        }

        var favBtn = e.target.closest(".igoshop-btn-fav");
        if (favBtn) {
            var card2 = favBtn.closest("[data-sku]");
            if (!card2) return;
            e.preventDefault();
            var item2 = getItemFromNode(card2);
            console.log("item2", item2);
            addToFav(item2);
            return;
        }
    });

    // Инициализация на загрузке страницы
    document.addEventListener("DOMContentLoaded", function () {
        addStyles();
        createQuantityControls();
        updateGoToCartButton();
    });
})();
