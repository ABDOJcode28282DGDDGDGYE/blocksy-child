/**
 * checkout.js — blocksy-child/checkout-assets/checkout.js
 */
(function($) {
    'use strict';

    /* ==================================================
       COUNTRIES DATA
    ================================================== */
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

    /* ==================================================
       PHONE DROPDOWN
    ================================================== */
    function initPhone() {
        if ( document.getElementById('cko-phone-wrap') ) return;

        var $original = $('#billing_phone');
        if ( !$original.length ) return;

        var selected  = COUNTRIES[0];
        var $wrapper  = $original.closest('.woocommerce-input-wrapper');

        var html =
            '<div class="cko-phone-wrap" id="cko-phone-wrap">' +

              '<button type="button" class="cko-phone-btn" id="cko-phone-btn" aria-expanded="false" aria-label="Select country code">' +
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
                  '<input type="text" id="cko-psearch" placeholder="Search country..." autocomplete="off">' +
                '</div>' +
                '<ul class="cko-phone-list" id="cko-phone-list"></ul>' +
              '</div>' +

              '<input type="tel" class="cko-phone-inp" id="cko-phone-inp" ' +
                'placeholder="Enter your phone number" autocomplete="tel" aria-label="Phone number">' +

            '</div>';

        $wrapper.append(html);

        var btn      = document.getElementById('cko-phone-btn');
        var drop     = document.getElementById('cko-phone-drop');
        var search   = document.getElementById('cko-psearch');
        var realInp  = document.getElementById('cko-phone-inp');
        var flag     = document.getElementById('cko-flag');
        var dial     = document.getElementById('cko-dial');
        var list     = document.getElementById('cko-phone-list');

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

        function openDropdown() {
            drop.classList.add('open');
            btn.setAttribute('aria-expanded', 'true');
            setTimeout(function(){ search.focus(); }, 40);
        }

        function closeDropdown() {
            drop.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }

        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            drop.classList.contains('open') ? closeDropdown() : openDropdown();
        });

        document.addEventListener('click', function(e) {
            if ( !document.getElementById('cko-phone-wrap').contains(e.target) ) closeDropdown();
        });

        search.addEventListener('input', function() {
            var q = this.value.toLowerCase();
            renderList( q ? COUNTRIES.filter(function(c){ return c.name.toLowerCase().includes(q) || c.code.includes(q); }) : COUNTRIES );
        });

        realInp.addEventListener('input', syncValue);

        renderList(COUNTRIES);
    }

    /* ==================================================
       INIT
    ================================================== */
    $(document).ready(function() {
        initPhone();
    });

    // Re-init after WooCommerce AJAX update
    $(document.body).on('updated_checkout', function() {
        initPhone();
    });

})(jQuery);
