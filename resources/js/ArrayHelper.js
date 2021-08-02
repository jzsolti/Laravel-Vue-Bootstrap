export default class FormHelper {

    static sortByString(items, name ) {
        return items.sort(function (a, b) {
            var nameA = a[name].toUpperCase(); // ignore upper and lowercase
            var nameB = b[name].toUpperCase(); // ignore upper and lowercase
            if (nameA < nameB) {
                return -1;
            }
            if (nameA > nameB) {
                return 1;
            }

            // names must be equal
            return 0;
        });

    }

    static sortByNumber(items, name) {
        return items.sort(function (a, b) {
            return a[name] - b[name];
        });
    }







}