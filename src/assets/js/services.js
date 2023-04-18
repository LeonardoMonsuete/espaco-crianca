
function redirect(url) {
    if (url == null) {
        alert('Url ' + url + ' inválida');
        return false;
    }

    window.location.href = url;
}

function searchPresencesByDate() {
    let startDate = document.getElementById('startDate').value
    let endDate = document.getElementById('endDate').value

    if (startDate == null || startDate == '') {
        alert('Data de inicio não pode ser vazia')
        return false;
    }

    if (endDate == null || endDate == '') {
        alert('Data final não pode ser vazia')
        return false;
    }

    if (startDate.match(/^(\d{2})\/(\d{2})\/(\d{4})$/)) {
        alert('Formato de data inicial inválida')
        return false;
    }

    if (endDate.match(/^(\d{2})\/(\d{2})\/(\d{4})$/)) {
        alert('Formato de data final inválida')
        return false;
    }

    redirect('presencas.php?startDate=' + startDate + '&endDate=' + endDate)
}

function logout() {
    window.localStorage.clear()
    window.location.href = "login.php"
}

function changeStatus(userId, currentStatus, nome) {
    let newStatusText = currentStatus == 1 ? 'inativar' : 'ativar'
    let data = { 'userId': userId, 'newStatus': currentStatus == 1 ? 0 : 1, 'action': 'changeStatus' }

    var formData = new FormData();

    Object.keys(data).forEach(key => {
        if (data[key].type !== undefined && data[key].type.includes('image/')) {
            formData.append(key, data[key], data[key].name);
        } else {
            formData.append(key, data[key]);
        }
    });




    if (confirm('Deseja realmente ' + newStatusText + ' o aluno ' + nome + ' ?')) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", './src/controllers/admin/studentsController.php', true);
        xhr.send(formData);
        xhr.onreadystatechange = () => { // Call a function when the state changes.
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {

                let response = JSON.parse(xhr.responseText);
                if (response.status === 1) {
                    alert(response.msg)
                    window.location.reload();
                }

            } else {
                alert(response.msg)
            }
        }
    }

    return false;


}

function validateEmailFormat(email) {
    if (email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)) {
        return true;
    }
    return false;
}
/**
 * Checks that an element has a non-empty `name` and `value` property.
 * @param  {Element} element  the element to check
 * @return {Bool}             true if the element is an input, false if not
 */
const isValidElement = element => {
    return element.name && element.value;
};

/**
 * Checks if an element’s value can be saved (e.g. not an unselected checkbox).
 * @param  {Element} element  the element to check
 * @return {Boolean}          true if the value should be added, false if not
 */
const isValidValue = element => {
    return (!['checkbox', 'radio'].includes(element.type) || element.checked);
};

/**
 * Checks if an input is a checkbox, because checkboxes allow multiple values.
 * @param  {Element} element  the element to check
 * @return {Boolean}          true if the element is a checkbox, false if not
 */
const isCheckbox = element => element.type === 'checkbox';

const isImage = element => element.type === 'file';

/**
 * Checks if an input is a `select` with the `multiple` attribute.
 * @param  {Element} element  the element to check
 * @return {Boolean}          true if the element is a multiselect, false if not
 */
const isMultiSelect = element => element.options && element.multiple;

/**
 * Retrieves the selected options from a multi-select as an array.
 * @param  {HTMLOptionsCollection} options  the options for the select
 * @return {Array}                          an array of selected option values
 */
const getSelectValues = options => [].reduce.call(options, (values, option) => {
    return option.selected ? values.concat(option.value) : values;
}, []);

/**
 * A more verbose implementation of `formToJSON()` to explain how it works.
 *
 * NOTE: This function is unused, and is only here for the purpose of explaining how
 * reducing form elements works.
 *
 * @param  {HTMLFormControlsCollection} elements  the form elements
 * @return {Object}                               form data as an object literal
 */
const formToJSON_deconstructed = elements => {

    // This is the function that is called on each element of the array.
    const reducerFunction = (data, element) => {

        // Add the current field to the object.
        data[element.name] = element.value;

        // For the demo only: show each step in the reducer’s progress.
        //console.log(JSON.stringify(data));

        return data;
    };

    // This is used as the initial value of `data` in `reducerFunction()`.
    const reducerInitialValue = {};

    // To help visualize what happens, log the inital value, which we know is `{}`.
    //console.log('Initial `data` value:', JSON.stringify(reducerInitialValue));

    // Now we reduce by `call`-ing `Array.prototype.reduce()` on `elements`.
    const formData = [].reduce.call(elements, reducerFunction, reducerInitialValue);

    // The result is then returned for use elsewhere.
    return formData;
};

/**
 * Retrieves input data from a form and returns it as a JSON object.
 * @param  {HTMLFormControlsCollection} elements  the form elements
 * @return {Object}                               form data as an object literal
 */
const formToJSON = elements => [].reduce.call(elements, (data, element) => {

    // Make sure the element has the required properties and should be added.
    if (isValidElement(element) && isValidValue(element)) {

        /*
         * Some fields allow for more than one value, so we need to check if this
         * is one of those fields and, if so, store the values as an array.
         */

        if (isCheckbox(element)) {
            let value = element.checked ? 1 : 0
            data[element.name] = (data[element.name] || []).concat(value);
        } else if (isMultiSelect(element)) {
            data[element.name] = getSelectValues(element);
        } else if (isImage(element)) {
            // console.log(element.files[0])
            data[element.name] = element.files[0];
        } else {
            data[element.name] = element.value;
        }
    }

    return data;
}, {});

/**
 * A handler function to prevent default submission and run our custom script.
 * @param  {Event} event  the submit event triggered by the user
 * @return {void}
 */
const handleFormSubmit = event => {

    // Stop the form from submitting since we’re handling that with AJAX.
    event.preventDefault();

    // Call our function to get the form data.
    var formData = new FormData();
    const data = formToJSON(form.elements);

    Object.keys(data).forEach(key => {
        if (data[key].type !== undefined && data[key].type.includes('image/')) {
            formData.append(key, data[key], data[key].name);
        } else {
            formData.append(key, data[key]);
        }
    });
    formData.append('action', 'insertUpdate')
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    if (urlParams.get('update') == 'true') {
        formData.append('isUpdate', true)
        formData.append('uid', urlParams.get('id'))
    }

    if (form.action.includes('userController')) {
        if (!validateEmailFormat(data.email)) {
            alert('Formato do e-mail inválido')
            return false;
        }
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", form.action, true);

    //Send the proper header information along with the request
    // xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(formData);
    xhr.onreadystatechange = () => { // Call a function when the state changes.
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            console.log(xhr)
            let response = JSON.parse(xhr.responseText);
            if (response.status === 1) {
                if (response.urlLocation === 'admin-index.php') {
                    window.localStorage.setItem('userLoggedNome', response.loggedUserData.nome);
                    window.localStorage.setItem('userLoggedId', response.loggedUserData.id);
                    window.location.href = response.urlLocation;
                }

                if (response.urlLocation !== 'admin-index.php') {
                    alert(response.msg)
                    window.location.href = response.urlLocation;
                }

            } else {
                alert(response.msg)
                window.location.href = response.urlLocation;
            }
        }
    }


    // ...this is where we’d actually do something with the form data...
};

/*
 * This is where things actually get started. We find the form element using
 * its class name, then attach the `handleFormSubmit()` function to the 
 * `submit` event.
 */
const form = document.getElementsByTagName('form')[0];
if (form !== undefined) {
    form.addEventListener('submit', handleFormSubmit);
}



$(document).ready(function () {
    $('.general-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excel', className: 'btn btn-success', text: '<i style="font-size:26px;" class="bx bx-spreadsheet"></i>' },
            { extend: 'pdf', className: 'btn btn-danger', orientation: 'portrait', pageSize: 'A4', text: '<i style="font-size:26px;" class="bx bxs-file-pdf"></i>', title: 'Lista de presenças' },
            { extend: 'print', className: 'btn btn-secondary', text: '<i style="font-size:26px;" class="bx bxs-printer"></i>' }
        ],
        responsive: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json"
        },
    }).draw(true).columns.adjust();


    $(".loading-spinner-div")[0].classList.add('fade-out');
    delay(200).then(() => $(".loading-spinner-div").hide("slow"));
 

});

function delay(time) {
    return new Promise(resolve => setTimeout(resolve, time));
}

var nods = document.getElementsByClassName('img-miniature');
for (var i = 0; i < nods.length; i++) {
    nods[i].attributes['src'].value += "?a=" + Math.random();
}

