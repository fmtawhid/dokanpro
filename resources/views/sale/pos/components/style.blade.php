<style>
    :root {
        --theme-color: {{ $mainShop?->shopCategory?->primary_color ?? '#29aae1' }};
        /* --theme-secondary-color: {{ $mainShop?->shopCategory?->secondary_color ?? '#eaf7fc' }}; */
        --theme-secondary-color: {{ $mainShop?->shopCategory?->secondary_color ?? '#eaf7fc' }};


    }

    body {
        margin: 0;
        font-family: "Vazirmatn", sans-serif;
        font-size: 0.88rem;
        font-weight: 400;
        line-height: 1.5;
        color: #43475d;
        text-align: left;
        overflow-x: hidden;
    }

    .animated-element {
        color: #1f2937;
        animation: myAnimation 1s ease-in-out infinite;
        transition: 1s ease-in-out;
    }

    @keyframes myAnimation {
        0% {
            color: #d1d5db;
        }

        100% {
            color: #1f2937;
        }
    }

    @media (max-width:420px) {
        #dateTimeSection {
            display: none;
        }
    }

    @media (max-width:635px) {
        #categoryBrandSection {
            display: none;
        }

        #productListSection {
            display: none;
        }
    }

    .customScroll::-webkit-scrollbar {
        width: 5px !important;
    }

    .customScroll::-webkit-scrollbar-thumb {
        border-radius: 8px;
        background: #ddd;
    }

    tfoot {
        background-color: #f0f0f0;
        display: table-footer-group;
    }

    tfoot::before {
        content: '';
        position: absolute;
        z-index: -1;
        width: 100%;
        height: 100%;
        background: #f0f0f0;
    }

    .customActive {
        background-color: var(--theme-color) !important;
        color: #FFFFFF !important;
    }

    @media print {
        * {
            font-size: 12px;
            line-height: 20px;
        }

        body[data-pdfjsprinting] {
            overflow-y: visible;
            width: 100%;
            height: 100%;
        }

        td,
        th {
            padding: 5px 0;
        }

        @page {
            margin: 1.5cm 0.5cm 0.5cm;
            page-break-after: always;
            page-break-inside: avoid;
            page-break-before: avoid;
        }

        @page: first {
            margin-top: 0.5cm;
        }
    }

    .primary-text-color {
        color: var(--theme-color)
    }

    .primary-text-color-light {
        color: var(--theme-secondary-color)
    }

    .primary-bg-color {
        background: var(--theme-color)
    }

    .primary-bg-color-light {
        background: var(--theme-secondary-color)
    }

    .primary-border-color {
        border-color: var(--theme-color)
    }

    .primary-border-color-light {
        border-color: var(--theme-secondary-color) !important;
    }

    .hover-primary-border-color:hover {
        border-color: var(--theme-secondary-color) !important;
        background: var(--theme-secondary-color) !important;
    }

    .hover-bg-primary-color-rgb {
        background: #70809082;

    }
</style>
