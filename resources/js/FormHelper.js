export default class FormHelper{

    static inputClass(inputName, errors) {

        if (Object.keys(errors).length === 0) {
            return '';
        } else if (inputName in errors) {
            return 'is-invalid';
        } else {
            return 'is-valid';
        }
    }
}