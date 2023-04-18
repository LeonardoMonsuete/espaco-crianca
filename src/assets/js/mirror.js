/**
 * jQuery plugin which provides text and images in the mirror writing.
 *
 * MIT License
 *
 * @author s1r-J
 * @see https://github.com/s1r-J/jquery.mirror
 */
$.widget('ui.mirror', {
    options: {
        char: false, // mirroring for each 1 char.
        horizontal: true, // reflect across y-axis.
        vertical: false, // reflect across x-axis.
    },

    _init: function () {

        if (this.options.char) {
            this.mirrorChar();
        } else {
            this.mirror();
        }
    },

    mirror: function () {

        let $target = $(this.element);
        if ($target.css('display') == 'inline') {
            $target.css({
                display: 'inline-block',
            });
        }

        let transform = '';
        if (this.options.horizontal) {
            transform += 'scaleX(-1)';
        }
        if (this.options.vertical) {
            transform += 'scaleY(-1)';
        }
        $target.css({
            transform: transform,
        });
    },

    mirrorChar: function () {

        let $target = $(this.element);

        let $style = $('<style> .mirrorH-char {display:inline-block; transform: scaleX(-1);} .mirrorV-char {display:inline-block; transform: scaleY(-1);} .mirrorHV-char {display:inline-block; transform: scale(-1, -1);} </style>');
        $('head').append($style);

        this._mirroring($target);
    },

    _mirroring: function ($target) {

        if ($target.childElementCount != 0) {
            let children = $target.children();
            for (let child of children) {
                this._mirroring($(child));
            }
        }

        let text = this._getSurfaceText($target).trim();
        if (text.length != 0) {
            $target.text('');

            let cssClass = 'mirrorH-char';
            if (this.options.horizontal && this.options.vertical) {
                cssClass = 'mirrorHV-char';
            } else if (this.options.vertical) {
                cssClass = 'mirrorV-char'
            } else if (!this.options.horizontal) {
                return;
            }
            let arr = text.split('');
            for (let item of arr) {
                if (item.match(/\s/g)) {
                    $target.append(item);
                } else {
                    $target.append($('<span>', {
                        text: item,
                        class: cssClass,
                    }));
                }
            }
        }
    },

    _getSurfaceText: function (selector) {

        let $elem = $(selector[0].outerHTML);
        $elem.children().empty();

        return $elem.text();
    },
});
