<?php
/* @var $this yii\web\View */
/* @var $biblioevent app\models\Biblioevents */
/* @var $ticketCategories app\models\TicketCategories[] */
/* @var $guides app\models\Guide[] */
/* @var $selectedDate string|null */
/* @var $widgetId string */
/* @var $availableDates array */

use yii\helpers\Html;
use yii\helpers\Url;

$getSessionsUrl = Url::to(['/experience/booking/get-sessions']);
$createBookingUrl = Url::to(['/experience/booking/create-booking']);
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
?>

<div id="excursion-booking-widget-<?= $widgetId ?>" class="excursion-booking-widget" data-biblioevent-id="<?= $biblioevent->id ?>" data-get-sessions-url="<?= Html::encode($getSessionsUrl) ?>" data-create-booking-url="<?= Html::encode($createBookingUrl) ?>" data-csrf-param="<?= Html::encode($csrfParam) ?>" data-csrf-token="<?= Html::encode($csrfToken) ?>">
    <?php if (!empty($guides)): ?>
    <div class="excursion-guide-block">
        <?php foreach ($guides as $index => $guide): ?>
            <?php if ($index === 0): ?>
                <?php $person = $guide->person; ?>
                <div class="excursion-guide-main">
                    <div class="excursion-guide-name"><?= Html::encode(trim(($person->name ?? '') . ' ' . ($person->second_name ?? ''))) ?></div>
                    <div class="excursion-guide-stats">
                        <?php if ($guide->rating > 0): ?>
                            <span class="excursion-guide-rating">★ <?= $guide->rating ?></span>
                        <?php endif; ?>
                        <?php if ($guide->tours_count > 0): ?>
                            <span class="excursion-guide-tours"><?= $guide->tours_count ?> посетили</span>
                        <?php endif; ?>
                        <?php if ($guide->experience_start_year): ?>
                            <span class="excursion-guide-experience"><?= (date('Y') - $guide->experience_start_year) ?> года на Трипстере</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($guide->guides_count > 1): ?>
                        <div class="excursion-guide-team">представитель команды гидов</div>
                    <?php endif; ?>
                    <?php if (!empty($guide->description)): ?>
                        <div class="excursion-guide-description"><?= Html::encode($guide->description) ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="excursion-calendar-section">
        <h3>Выберите дату</h3>
        <div class="excursion-calendar-container" id="excursion-calendar-<?= $widgetId ?>"></div>
        <div class="excursion-calendar-legend">
            <span class="excursion-legend-item excursion-legend-busy">День занят</span>
            <span class="excursion-legend-item excursion-legend-available">День свободен</span>
        </div>
    </div>

    <div class="excursion-sessions-section" style="display: none;">
        <h3>Выберите время</h3>
        <div class="excursion-sessions-list" id="excursion-sessions-<?= $widgetId ?>"></div>
    </div>

    <div class="excursion-booking-form-section" style="display: none;">
        <h3>Сколько вас будет</h3>
        <div class="excursion-tickets-list" id="excursion-tickets-list-<?= $widgetId ?>">
            <?php foreach ($ticketCategories as $category): ?>
            <div class="excursion-ticket-item" data-category-id="<?= $category->id ?>" data-price="<?= $category->price ?>">
                <div class="excursion-ticket-info">
                    <span class="excursion-ticket-name"><?= Html::encode($category->name) ?></span>
                    <span class="excursion-ticket-price">
                        <?= $category->price > 0 ? Yii::$app->formatter->asCurrency($category->price) : 'бесплатно' ?>
                    </span>
                </div>
                <div class="excursion-ticket-counter">
                    <button type="button" class="excursion-counter-btn excursion-counter-minus" disabled>-</button>
                    <input type="number" class="excursion-counter-input" value="0" min="0" max="25" readonly>
                    <button type="button" class="excursion-counter-btn excursion-counter-plus">+</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="excursion-contact-form">
            <h3>Контактные данные</h3>
            <div class="excursion-form-group">
                <label>Как вас зовут *</label>
                <input type="text" class="excursion-form-control" name="customer_name" required>
            </div>
            <div class="excursion-form-group">
                <label>Ваша эл. почта *</label>
                <input type="email" class="excursion-form-control" name="customer_email" required>
            </div>
            <div class="excursion-form-group">
                <label>Ваш телефон *</label>
                <input type="tel" class="excursion-form-control" name="customer_phone" required placeholder="+7 (___) ___-__-__">
            </div>
            <div class="excursion-form-group">
                <label>Вопросы и комментарии</label>
                <textarea class="excursion-form-control" name="comment" rows="3"></textarea>
            </div>
        </div>

        <div class="excursion-booking-footer">
            <div class="excursion-total-price">
                Стоимость: <span class="excursion-total-amount">0 ₽</span> за <span class="excursion-total-persons">0</span> человек
            </div>
            <div class="excursion-booking-info">
                Вы можете сразу оплатить бронирование либо задать вопросы организатору.
            </div>
            <button type="button" class="excursion-btn-submit" id="excursion-submit-booking-<?= $widgetId ?>">
                Забронировать
            </button>
        </div>
    </div>
</div>

<?php
$availableDatesJson = json_encode($availableDates);
$widgetIdJs = $widgetId;
$css = <<<CSS
.excursion-booking-widget {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: #fff;
}
.excursion-guide-block { margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #eee; }
.excursion-guide-name { font-weight: 600; font-size: 1.1em; }
.excursion-guide-stats { color: #666; font-size: 0.9em; margin-top: 4px; }
.excursion-guide-stats span { margin-right: 12px; }
.excursion-guide-team { font-size: 0.85em; color: #888; margin-top: 4px; }
.excursion-guide-description { margin-top: 8px; color: #444; }
.excursion-calendar-section h3,
.excursion-sessions-section h3,
.excursion-booking-form-section h3,
.excursion-contact-form h3 { margin: 16px 0 12px; font-size: 1em; }
.excursion-calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; margin: 12px 0; }
.excursion-calendar-weekday { text-align: center; font-weight: 600; font-size: 0.85em; padding: 4px 0; }
.excursion-calendar-day {
    padding: 8px; text-align: center; border-radius: 4px; cursor: pointer;
    border: 1px solid #ddd; background: #f9f9f9;
}
.excursion-calendar-day.available { background: #e8f5e9; cursor: pointer; }
.excursion-calendar-day.available:hover { background: #c8e6c9; }
.excursion-calendar-day.selected { background: #4caf50; color: #fff; }
.excursion-calendar-day.disabled { opacity: 0.7; cursor: not-allowed; }
.excursion-calendar-day.disabled.past { background: #e0e0e0; color: #999; }
.excursion-calendar-day.disabled.future { background: #ffebee; color: #c62828; }
.excursion-calendar-day.disabled.no-sessions { background: #ffebee; color: #c62828; }
.excursion-calendar-legend { font-size: 0.85em; color: #666; margin-top: 8px; }
.excursion-legend-item { margin-right: 16px; }
.excursion-sessions-list { display: flex; flex-wrap: wrap; gap: 8px; margin: 12px 0; }
.excursion-session-btn {
    padding: 10px 16px; border: 1px solid #4caf50; border-radius: 4px;
    background: #fff; cursor: pointer;
}
.excursion-session-btn:hover { background: #e8f5e9; }
.excursion-session-btn.selected { background: #4caf50; color: #fff; }
.excursion-ticket-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #eee; }
.excursion-ticket-info { display: flex; justify-content: space-between; width: 60%; }
.excursion-ticket-counter { display: flex; align-items: center; gap: 4px; }
.excursion-counter-btn { width: 32px; height: 32px; border: 1px solid #ddd; border-radius: 4px; background: #f5f5f5; cursor: pointer; }
.excursion-counter-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.excursion-counter-input { width: 40px; text-align: center; border: 1px solid #ddd; border-radius: 4px; padding: 4px; }
.excursion-form-group { margin-bottom: 12px; }
.excursion-form-control { width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; color: black; }
.excursion-form-control::placeholder { color: #666; }
input.excursion-form-control[name="customer_phone"] { color: black; }
.excursion-booking-footer { margin-top: 24px; padding-top: 16px; border-top: 1px solid #eee; }
.excursion-total-price { font-weight: 600; margin-bottom: 8px; }
.excursion-booking-info { font-size: 0.9em; color: #666; margin-bottom: 12px; }
.excursion-btn-submit { padding: 12px 24px; background: #4caf50; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
.excursion-btn-submit:hover { background: #43a047; }
.excursion-btn-submit:disabled { background: #9e9e9e; cursor: not-allowed; }
.excursion-calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.excursion-calendar-nav { padding: 4px 12px; cursor: pointer; border: 1px solid #ddd; border-radius: 4px; background: #f5f5f5; }
.excursion-calendar-nav:disabled { opacity: 0.5; cursor: not-allowed; }
CSS;

$script = <<<JS
(function() {
    var availableDates = $availableDatesJson;
    var widgetId = '$widgetIdJs';
    var container = document.getElementById('excursion-booking-widget-' + widgetId);
    if (!container) return;

    var biblioeventId = container.dataset.biblioeventId;
    var getSessionsUrl = container.dataset.getSessionsUrl;
    var createBookingUrl = container.dataset.createBookingUrl;
    var csrfParam = container.dataset.csrfParam;
    var csrfToken = container.dataset.csrfToken;

    var selectedDate = null;
    var selectedSession = null;
    var selectedEventId = null;
    var today = new Date();
    today.setHours(0, 0, 0, 0);
    var currentMonth = today.getMonth();
    var currentYear = today.getFullYear();
    var maxDate = new Date(today);
    maxDate.setFullYear(maxDate.getFullYear() + 1);
    var maxYear = maxDate.getFullYear();
    var maxMonth = maxDate.getMonth();

    function isPastMonth(y, m) {
        return y < today.getFullYear() || (y === today.getFullYear() && m < today.getMonth());
    }
    function isBeyondLimit(y, m) {
        return y > maxYear || (y === maxYear && m > maxMonth);
    }

    function renderCalendar() {
        var cal = document.getElementById('excursion-calendar-' + widgetId);
        if (!cal) return;

        var d = new Date(currentYear, currentMonth, 1);
        var lastDay = new Date(currentYear, currentMonth + 1, 0).getDate();
        var firstDow = d.getDay();
        firstDow = firstDow === 0 ? 6 : firstDow - 1;

        var canGoLeft = !isPastMonth(currentYear, currentMonth);
        var canGoRight = !isBeyondLimit(currentYear, currentMonth);

        var monthNames = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'];
        var html = '<div class="excursion-calendar-header">';
        html += '<button type="button" class="excursion-calendar-nav" data-dir="-1"' + (canGoLeft ? '' : ' disabled') + '>←</button>';
        html += '<span>' + monthNames[currentMonth] + ' ' + currentYear + '</span>';
        html += '<button type="button" class="excursion-calendar-nav" data-dir="1"' + (canGoRight ? '' : ' disabled') + '>→</button>';
        html += '</div><div class="excursion-calendar-grid">';
        ['Пн','Вт','Ср','Чт','Пт','Сб','Вс'].forEach(function(d) { html += '<div class="excursion-calendar-weekday">' + d + '</div>'; });

        for (var i = 0; i < firstDow; i++) html += '<div class="excursion-calendar-day disabled"></div>';
        for (var day = 1; day <= lastDay; day++) {
            var y = currentYear, m = currentMonth + 1;
            var dateStr = y + '-' + (m < 10 ? '0' : '') + m + '-' + (day < 10 ? '0' : '') + day;
            var dayDate = new Date(y, currentMonth, day);
            var isPast = dayDate < today;
            var isBeyondYear = dayDate > maxDate;
            var hasSessions = availableDates.indexOf(dateStr) !== -1;
            var isAvailable = !isPast && !isBeyondYear && hasSessions;
            var isSelected = selectedDate === dateStr;
            var cls = 'excursion-calendar-day';
            if (isPast) { cls += ' disabled past'; }
            else if (isBeyondYear) { cls += ' disabled future'; }
            else if (!isPast && !isBeyondYear && !hasSessions) { cls += ' disabled no-sessions'; }
            else if (isAvailable) cls += ' available';
            if (isSelected) cls += ' selected';
            html += '<div class="' + cls + '" data-date="' + dateStr + '">' + day + '</div>';
        }
        html += '</div>';
        cal.innerHTML = html;

        cal.querySelectorAll('.excursion-calendar-day.available').forEach(function(el) {
            el.addEventListener('click', function() {
                selectedDate = this.dataset.date;
                renderCalendar();
                loadSessions();
            });
        });
        cal.querySelectorAll('.excursion-calendar-nav:not([disabled])').forEach(function(el) {
            el.addEventListener('click', function() {
                var dir = parseInt(this.dataset.dir, 10);
                currentMonth += dir;
                if (currentMonth > 11) { currentYear++; currentMonth = 0; }
                if (currentMonth < 0) { currentYear--; currentMonth = 11; }
                renderCalendar();
            });
        });
    }

    function loadSessions() {
        if (!selectedDate) return;
        var list = document.getElementById('excursion-sessions-' + widgetId);
        var section = list.closest('.excursion-sessions-section');
        list.innerHTML = 'Загрузка...';
        section.style.display = 'block';

        var fd = new FormData();
        fd.append('date', selectedDate);
        fd.append('biblioevent_id', biblioeventId);
        fd.append(csrfParam, csrfToken);

        fetch(getSessionsUrl, { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                list.innerHTML = '';
                if (data.success && data.sessions && data.sessions.length) {
                    selectedEventId = data.event_id;
                    data.sessions.forEach(function(s) {
                        var btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'excursion-session-btn';
                        btn.dataset.sessionId = s.id;
                        btn.dataset.multiplier = s.price_multiplier || 1;
                        btn.textContent = s.time_start + ' - ' + s.time_end + ' (' + s.available + ' мест)';
                        btn.addEventListener('click', function() {
                            selectedSession = parseInt(this.dataset.sessionId, 10);
                            list.querySelectorAll('.excursion-session-btn').forEach(function(b) { b.classList.remove('selected'); });
                            this.classList.add('selected');
                            document.querySelector('.excursion-booking-form-section').style.display = 'block';
                            updateTotal();
                        });
                        list.appendChild(btn);
                    });
                } else {
                    list.innerHTML = 'Нет доступных сеансов на эту дату.';
                }
            })
            .catch(function() { list.innerHTML = 'Ошибка загрузки.'; });
    }

    function updateTotal() {
        var total = 0, persons = 0;
        var multiplier = selectedSession ? (document.querySelector('.excursion-session-btn.selected')?.dataset.multiplier || 1) : 1;
        container.querySelectorAll('.excursion-ticket-item').forEach(function(item) {
            var qty = parseInt(item.querySelector('.excursion-counter-input').value, 10) || 0;
            var price = parseFloat(item.dataset.price) || 0;
            total += price * qty * multiplier;
            persons += qty;
        });
        var totalEl = container.querySelector('.excursion-total-amount');
        var personsEl = container.querySelector('.excursion-total-persons');
        if (totalEl) totalEl.textContent = Math.round(total) + ' ₽';
        if (personsEl) personsEl.textContent = persons;
    }

    container.querySelectorAll('.excursion-ticket-item').forEach(function(item) {
        var minus = item.querySelector('.excursion-counter-minus');
        var plus = item.querySelector('.excursion-counter-plus');
        var input = item.querySelector('.excursion-counter-input');
        var max = 25;
        function updateBtns() {
            var v = parseInt(input.value, 10) || 0;
            minus.disabled = v <= 0;
            plus.disabled = v >= max;
        }
        minus.addEventListener('click', function() {
            var v = Math.max(0, (parseInt(input.value, 10) || 0) - 1);
            input.value = v;
            updateBtns();
            updateTotal();
        });
        plus.addEventListener('click', function() {
            var v = Math.min(max, (parseInt(input.value, 10) || 0) + 1);
            input.value = v;
            updateBtns();
            updateTotal();
        });
        updateBtns();
    });

    document.getElementById('excursion-submit-booking-' + widgetId).addEventListener('click', function() {
        var btn = this;
        var tickets = [];
        var totalQty = 0;
        container.querySelectorAll('.excursion-ticket-item').forEach(function(item) {
            var qty = parseInt(item.querySelector('.excursion-counter-input').value, 10) || 0;
            if (qty > 0) {
                tickets.push({
                    category_id: item.dataset.categoryId,
                    quantity: qty,
                    price: parseFloat(item.dataset.price) || 0
                });
                totalQty += qty;
            }
        });
        if (totalQty === 0) { alert('Выберите количество билетов.'); return; }
        if (!selectedSession) { alert('Выберите время.'); return; }
        var name = container.querySelector('input[name="customer_name"]').value.trim();
        var email = container.querySelector('input[name="customer_email"]').value.trim();
        var phone = container.querySelector('input[name="customer_phone"]').value.trim();
        if (!name || !email || !phone) { alert('Заполните контактные данные.'); return; }

        btn.disabled = true;
        var fd = new FormData();
        fd.append('session_id', selectedSession);
        fd.append('tickets', JSON.stringify(tickets));
        fd.append('customer_name', name);
        fd.append('customer_email', email);
        fd.append('customer_phone', phone);
        fd.append('comment', container.querySelector('textarea[name="comment"]').value || '');
        fd.append(csrfParam, csrfToken);

        fetch(createBookingUrl, { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success && data.payment_url) {
                    window.location.href = data.payment_url;
                } else {
                    alert(data.message || 'Ошибка бронирования');
                    btn.disabled = false;
                }
            })
            .catch(function() {
                alert('Ошибка сети');
                btn.disabled = false;
            });
    });

    renderCalendar();
})();
JS;

$this->registerCss($css);
$this->registerJs($script);
?>
