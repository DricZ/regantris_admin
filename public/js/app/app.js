import AutoNumeric from 'autonumeric';

document.addEventListener('DOMContentLoaded', () => {
    // Initialize AutoNumeric for each input
    new AutoNumeric('#currency', {
        currencySymbol: 'Rp. ',
        decimalPlaces: 2,
    });

    new AutoNumeric('.percent', {
        suffixText: '%',
        decimalPlaces: 2,
    });

    new AutoNumeric('.number', {
        decimalPlaces: 0,
    });
});
