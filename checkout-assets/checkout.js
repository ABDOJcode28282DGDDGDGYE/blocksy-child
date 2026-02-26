/**
 * checkout.js — blocksy-child/checkout-assets/checkout.js
 */
(function($) {
    'use strict';

    /* ══════════════════════════════════════════════
       COUNTRIES DATA
    ══════════════════════════════════════════════ */
    var COUNTRIES = [
        { code:'+1',   name:'United States',  iso:'us' },
        { code:'+1',   name:'Canada',         iso:'ca' },
        { code:'+44',  name:'United Kingdom', iso:'gb' },
        { code:'+33',  name:'France',         iso:'fr' },
        { code:'+49',  name:'Germany',        iso:'de' },
        { code:'+61',  name:'Australia',      iso:'au' },
        { code:'+34',  name:'Spain',          iso:'es' },
        { code:'+39',  name:'Italy',          iso:'it' },
        { code:'+31',  name:'Netherlands',    iso:'nl' },
        { code:'+32',  name:'Belgium',        iso:'be' },
        { code:'+41',  name:'Switzerland',    iso:'ch' },
        { code:'+46',  name:'Sweden',         iso:'se' },
        { code:'+47',  name:'Norway',         iso:'no' },
        { code:'+45',  name:'Denmark',        iso:'dk' },
        { code:'+358', name:'Finland',        iso:'fi' },
        { code:'+48',  name:'Poland',         iso:'pl' },
        { code:'+351', name:'Portugal',       iso:'pt' },
        { code:'+30',  name:'Greece',         iso:'gr' },
        { code:'+420', name:'Czech Republic', iso:'cz' },
        { code:'+43',  name:'Austria',        iso:'at' },
        { code:'+212', name:'Morocco',        iso:'ma' },
        { code:'+213', name:'Algeria',        iso:'dz' },
        { code:'+216', name:'Tunisia',        iso:'tn' },
        { code:'+20',  name:'Egypt',          iso:'eg' },
        { code:'+966', name:'Saudi Arabia',   iso:'sa' },
        { code:'+971', name:'UAE',            iso:'ae' },
        { code:'+962', name:'Jordan',         iso:'jo' },
        { code:'+90',  name:'Turkey',         iso:'tr' },
        { code:'+7',   name:'Russia',         iso:'ru' },
        { code:'+86',  name:'China',          iso:'cn' },
        { code:'+81',  name:'Japan',          iso:'jp' },
        { code:'+82',  name:'South Korea',    iso:'kr' },
        { code:'+91',  name:'India',          iso:'in' },
        { code:'+55',  name:'Brazil',         iso:'br' },
        { code:'+52',  name:'Mexico',         iso:'mx' },
        { code:'+54',  name:'Argentina',      iso:'ar' },
    ];

    /* ══════════════════════════════════════════════
       PHONE DROPDOWN
    ══════════════════════════════════════════════ */
    function initPhone() {
        // تجنب التكرار
        if ( document.getElementById('cko-phone-wrap') ) return;

        var $original = $('#billing_phone');
        if ( !$original.length ) return;

        var selected  = COUNTRIES[0];
        var $wrapper  = $original.closest('.woocommerce-input-wrapper');

        /* بناء HTML */
        var html =
            '<div class="cko-phone-wrap" id="cko-phone-wrap">' +

              '<button type="button" class="cko-phone-btn" id="cko-phone-btn" aria-expanded="false">' +
                '<img class="cko-flag" id="cko-flag" ' +
                  'src="https://flagcdn.com/20x15/' + selected.iso + '.png" ' +
                  'width="20" height="15" alt="' + selected.name + '">' +
                '<span id="cko-dial">' + selected.code + '</span>' +
                '<svg class="cko-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" ' +
                  'stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                  '<path d="m6 9 6 6 6-6"/>' +
                '</svg>' +
              '</button>' +

              '<div class="cko-phone-drop" id="cko-phone-drop">' +
                '<div class="cko-phone-search">' +
                  '<input type="text" id="cko-psearch" placeholder="Search country…" autocomplete="off">' +
                '</div>' +
                '<ul class="cko-phone-list" id="cko-phone-list"></ul>' +
              '</div>' +

              '<input type="tel" class="cko-phone-inp" id="cko-phone-inp" ' +
                'placeholder="Enter your phone number" autocomplete="tel">' +

            '</div>';

        $wrapper.append(html);

        /* referensi */
        var btn      = document.getElementById('cko-phone-btn');
        var drop     = document.getElementById('cko-phone-drop');
        var search   = document.getElementById('cko-psearch');
        var realInp  = document.getElementById('cko-phone-inp');
        var flag     = document.getElementById('cko-flag');
        var dial     = document.getElementById('cko-dial');
        var list     = document.getElementById('cko-phone-list');

        /* render list */
        function renderList(arr) {
            list.innerHTML = '';
            arr.forEach(function(c) {
                var li  = document.createElement('li');
                var a   = document.createElement('a');
                a.href  = '#';
                a.className = (c.iso === selected.iso && c.code === selected.code) ? 'active' : '';
                a.innerHTML =
                    '<img class="cko-flag" src="https://flagcdn.com/20x15/' + c.iso + '.png" ' +
                        'width="20" height="15" alt="' + c.name + '" loading="lazy">' +
                    '<span class="cko-cname">' + c.name + '</span>' +
                    '<span class="cko-ccode">' + c.code + '</span>';

                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    selected         = c;
                    flag.src         = 'https://flagcdn.com/20x15/' + c.iso + '.png';
                    flag.alt         = c.name;
                    dial.textContent = c.code;
                    syncValue();
                    closeDropdown();
                    search.value = '';
                    renderList(COUNTRIES);
                    realInp.focus();
                });

                li.appendChild(a);
                list.appendChild(li);
            });
        }

        function syncValue() {
            $original.val( selected.code + ' ' + (realInp.value || '') ).trigger('change');
        }

        function positionDropdown() {
            var rect = btn.getBoundingClientRect();
            drop.style.top = (rect.bottom + window.scrollY + 6) + 'px';
            drop.style.left = (rect.left + window.scrollX) + 'px';
            drop.style.width = Math.max(rect.width, 240) + 'px';
        }

        function openDropdown() {
            positionDropdown();
            drop.classList.add('open');
            btn.setAttribute('aria-expanded', 'true');
            setTimeout(function(){ search.focus(); }, 40);
        }

        function closeDropdown() {
            drop.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }

        /* events */
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            drop.classList.contains('open') ? closeDropdown() : openDropdown();
        });

        document.addEventListener('click', function(e) {
            if ( !document.getElementById('cko-phone-wrap').contains(e.target) ) closeDropdown();
        });

        // إعادة موضع الـ dropdown عند التمرير أو تغيير حجم النافذة
        window.addEventListener('scroll', function() {
            if (drop.classList.contains('open')) positionDropdown();
        }, true);

        window.addEventListener('resize', function() {
            if (drop.classList.contains('open')) positionDropdown();
        });

        search.addEventListener('input', function() {
            var q = this.value.toLowerCase();
            renderList( q ? COUNTRIES.filter(function(c){ return c.name.toLowerCase().includes(q) || c.code.includes(q); }) : COUNTRIES );
        });

        realInp.addEventListener('input', syncValue);

        renderList(COUNTRIES);
    }

    /* ══════════════════════════════════════════════
       DEVICE SELECTOR ENHANCEMENT
    ══════════════════════════════════════════════ */
    function initDeviceSelector() {
        var $deviceSelect = $('#device_streaming');
        if (!$deviceSelect.length) return;

        // تهيئة Select2 بخيارات جميلة
        if ($.fn.select2) {
            $deviceSelect.select2({
                placeholder: 'Choose the device you are using',
                allowClear: false,
                width: '100%',
                minimumResultsForSearch: -1
            });
        }

        // تغيير placeholder عند الاختيار
        $deviceSelect.on('select2:select', function(e) {
            var selected = e.params.data;
            console.log('[v0] Device selected:', selected.text);
        });
    }

    /* ══════════════════════════════════════════════
       PAYMENT METHOD HIGHLIGHT
    ══════════════════════════════════════════════ */
    function initPayment() {
        $(document).on('change', 'input[name="payment_method"]', function() {
            $('#payment ul.payment_methods li').css('border-color','');
            $(this).closest('li').css('border-color','var(--green)');
        });
        // تحديد الأول
        $('input[name="payment_method"]:first').trigger('change');
    }

    /* ══════════════════════════════════════════════
       VISIBILITY FIXER
    ══════════════════════════════════════════════ */
    function showAllFields() {
        // إظهار جميع الحقول والأقسام المخفية
        var selectors = [
            '.woocommerce-billing-fields',
            '.woocommerce-shipping-fields',
            '.woocommerce-additional-fields',
            '.woocommerce-account-fields',
            '.form-row',
            '.woocommerce-billing-fields__field-wrapper',
            '.woocommerce-shipping-fields__field-wrapper'
        ];

        selectors.forEach(function(selector) {
            var elements = document.querySelectorAll('.custom-checkout-page ' + selector);
            elements.forEach(function(el) {
                el.style.display = 'block';
                el.style.visibility = 'visible';
                el.style.opacity = '1';
            });
        });

        console.log('[v0] All checkout fields visibility fixed');
    }

    /* ══════════════════════════════════════════════
       INIT
    ══════════════════════════════════════════════ */
    $(document).ready(function() {
        // تأخير بسيط للتأكد من تحميل الـ HTML
        setTimeout(function() {
            showAllFields();
            initPhone();
            initDeviceSelector();
            initPayment();
        }, 100);
    });

    // إعادة التهيئة عند تحديث WooCommerce AJAX
    $(document.body).on('updated_checkout', function() {
        setTimeout(function() {
            showAllFields();
            initPhone();
            initDeviceSelector();
        }, 100);
    });

})(jQuery);
