Vue.filter('doneLabel', (value) => {
    if (value == 0) {
        return 'Não';
    }

    return 'Sim';
});

Vue.filter('formatDate', (value, format, locale) => {
    if (!format) {
        format = "L";
    }

    if (locale) {
        moment.locale(locale);
    }

    return moment(value).format(format);
});

Vue.filter('formatStatus', (value, bills) => {

    if (bills.length == false) {
        return "muted";
    }

    var count = 0;

    for (var i in bills) {
        if (!bills[i].done) {
            count++;
        }
    }

    if (count > 0) {
        if (bills[0].receivable) {
            return "success";
        }
        return "danger";
    }

    return "success";

});