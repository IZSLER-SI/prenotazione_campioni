var tipo_prenotazione = 'ufficiale';
var calendar;
var order;
var lab;
var slot;
var slot_partial;
var business;
var map = null;
var strutture;
var giorno_prefissato;

function generateCalendar() {
    istanza = $('#unica_istanza').is(":checked")
    var calendarEl = document.getElementById('calendar');
    var output = '';
    var type;
    switch (tipo_prenotazione) {
        case 'ufficiale':
            type = 'timeGridWeek'
            break;
        case 'autocontrollo':
            type = 'dayGridMonth'
            break;
        case 'chimici':
            type = 'dayGridMonth'
            break;
        default:
            break;
    }
    calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'it',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
        },
        timeZone: 'UTC+2',
        initialView: type,
        selectable: true,
        selectOverlap: function (event) {
            if (event.allDay == true) {
                return false;
            } else {
                return true;
            }
        },
        googleCalendarApiKey: 'AIzaSyAtEGsJQ4fzUl3hN0S6EtUPKGRu2QuXlR4',
        eventSources: [
            {
                url: './assets/lib/events.php?prenotazione=' + tipo_prenotazione + '&lab=' + lab + '&istanza=' + istanza
            },
            {
                googleCalendarId: 'it.italian#holiday@group.v.calendar.google.com',
                className: 'gcal_fest',
                color: '#ff9f89',
                allDay: true,
            }
        ],
        eventContent: function (info) {
            if (info.event._def.title == 'Mercoledì delle Ceneri') {
                info.isStart = false;
                info.isEnd = false;
                info.event._def.allDay = false;
                return false
            }
        },
        displayEventTime: true,
        slotLabelInterval: slot_partial,
        slotDuration: slot,
        eventBackgroundColor: '#4e5ecc',
        weekends: false,
        slotMinTime: business[1][0],
        slotMaxTime: business[1][1],

        businessHours: [{
            daysOfWeek: [1],
            startTime: business[1][0],
            endTime: business[1][1],
        },
            {
                daysOfWeek: [2],
                startTime: business[2][0],
                endTime: business[2][1],
            },
            {
                daysOfWeek: [3],
                startTime: business[3][0],
                endTime: business[3][1],
            },
            {
                daysOfWeek: [4],
                startTime: business[4][0],
                endTime: business[4][1],
            },
            {
                daysOfWeek: [5],
                startTime: business[5][0],
                endTime: business[5][1],
            },
        ],
        selectConstraint: type = 'ufficiale' ? "businessHours" : null,
        eventClassNames: 'eventclass',

        select: function (start, end, jsEvent, view) {
            var check = moment(moment(start.startStr).format('YYYY/MM/DD'));
            var today = moment(moment().format('YYYY/MM/DD'));
            var test = check.diff(today, 'days')
            var days = 1;
            if (tipo_prenotazione == 'ufficiale') {
                days = 1
            } else {
                days = 1;
            }
            if (test > days) {
                if (tipo_prenotazione == 'autocontrollo') {

                    var tot = 0;
                    $('input[name="autocontrollo_prove[]"]:checked').serializeArray().forEach((element) => {
                        tot += parseInt(element.value.split('-')[1]);
                    })
                    var campioni = parseInt($('#autocontrollo_n_campione').val());
                    var diff_prove = checkProve(lab, start.startStr) - (tot * campioni);
                    console.log(diff_prove)
                    if (diff_prove < 0) {
                        alertify.alert('Attenzione', 'Attenzione, sono stati selezionati troppi campioni per questo giorno, provare in un altra giornata.', function () { });
                    } else {
                        document.getElementById('date_prenotazione').value = start.startStr
                        alertify.set('notifier', 'position', 'top-center');
                        alertify.success('Data selezionata: ' + moment(start.startStr).format('DD/MM/YYYY'));
                    }

                } else {
                    var stat = checkEventiByDate(lab, start.startStr, start.endStr);
                    if (stat === 'false') {
                        alertify.alert('attenzione', 'Attenzione, ci sono eventi che vanno in conflitto con lo slot del ' + moment(start.startStr).format('DD/MM/YYYY [alle] hh:mm') + '. Scegliere un’altro slot.', function () { });
                        calendar.unselect();
                        return false;
                    }
                    if ((lab == '134' || lab == '19') && $('#ufficiale_matrice_select').val() == 17) {
                        var data_pren = moment(start.startStr).format('YYYY/MM/DD')
                        if (checkpren101(lab, data_pren) == "false") {
                            alertify.alert('attenzione', 'Attenzione per la categoria e prove selezionate, massimo una prenotazione al giorno. Nella data selezionata è già presente una prenotazione. Scegliere un’altra data.', function () { });
                        } else {
                            document.getElementById('date_prenotazione').value = start.startStr
                            alertify.set('notifier', 'position', 'top-center');
                            alertify.success('Data selezionata: ' + moment(start.startStr).format('DD/MM/YYYY'));
                        }
                    } else {
                        document.getElementById('date_prenotazione').value = start.startStr
                        alertify.set('notifier', 'position', 'top-center');
                        alertify.success('Data selezionata: ' + moment(start.startStr).format('DD/MM/YYYY'));
                    }
                }
            } else {
                alertify.alert('Attenzione', 'Attenzione, è necessario prenotare almeno ' + days + ' giorni prima dalla data del conferimento.', function () { });
                calendar.unselect()
            }
        },
        dateClick: function (info) {
            if (tipo_prenotazione == 'autocontrollo' || tipo_prenotazione == 'conoscitivo' || tipo_prenotazione == 'chimici') {
                let ev = calendar.getEvents()
                var check = moment(moment(info.dateStr).format('YYYY/MM/DD'));
                var today = moment(moment().format('YYYY/MM/DD'));
                var test = check.diff(today, 'days')
                var days = 1;
                if (tipo_prenotazione == 'ufficiale') {
                    days = 1
                } else {
                    days = 1;
                }
                if (test > days) {
                    if (tipo_prenotazione == 'autocontrollo' || tipo_prenotazione == 'conoscitivo' || tipo_prenotazione == 'chimici') {
                        var tot = 0;
                        $('input[name="autocontrollo_prove[]"]:checked').serializeArray().forEach((element) => {
                            tot += parseInt(element.value.split('-')[1]);
                        })
                        if (tipo_prenotazione == 'autocontrollo') {
                            var campioni = parseInt($('#autocontrollo_n_campione').val());
                        } else {
                            var campioni = 1
                        }
                        let diff_prove = 0
                        if (tipo_prenotazione == 'conoscitivo') {
                            diff_prove = checkProve(lab, info.dateStr) - 1
                        } else {
                            if($('#autocontrollo_campione').val() == '2'){
                                diff_prove = checkProve(lab, info.dateStr) - 1
                            }else{
                                diff_prove = checkProve(lab, info.dateStr) - (tot * campioni);
                            }
                        }
                        if (diff_prove < 0) {
                            alertify.alert('Attenzione', 'Attenzione, sono stati selezionati troppi campioni per questo giorno, provare in un altra giornata.', function () { });
                        } else {
                            if (diff_prove > 9000) {
                                alertify.alert('Attenzione', 'Campioni Ufficiali Conoscitivi: il laboratorio non accetta prenotazioni.', function () { });
                            } else {
                                document.getElementById('date_prenotazione').value = info.dateStr
                                alertify.set('notifier', 'position', 'top-center');
                                alertify.success('Data selezionata: ' + moment(info.dateStr).format('DD/MM/YYYYY'));
                            }
                        }
                    } else {
                        document.getElementById('date_prenotazione').value = info.dateStr
                        alertify.set('notifier', 'position', 'top-center');
                        alertify.success('Data selezionata: ' + moment(info.dateStr).format('DD/MM/YYYY'));
                    }
                } else {
                    alertify.alert('Attenzione', 'Attenzione, è necessario prenotare almeno ' + days + ' giorni prima dalla data del conferimento.', function () { });
                    calendar.unselect()

                }
            }
        }
    });
    calendar.render();
    if (tipo_prenotazione == 'ufficiale') {
        setInterval(function () {
            getSlots();
            calendar.refetchEvents();
            console.log('refetchEvents');
        }, 5000);
    }
}
function print_elem(elem) {
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
    mywindow.document.write('<html><head><title>' + document.title + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + document.title + '</h1>');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');
    mywindow.document.close();
    mywindow.focus();
    mywindow.print();
    return true;
}
function delay() {
    var animationDuration = 700;
    showLab()
    setTimeout(function () {
        generateCalendar()
    }, animationDuration);
}
function generateFinalita() {
    let dropdown = $('#ufficiale_finalita');
    let dropdown2 = $('#conoscitivo_finalita_inline');
    let dropdown3 = $('#chimici_finalita');
    let strut = $('#autocontrollo_strutttura option:selected').val();
    dropdown.empty();
    dropdown.append('<option value="" selected="true" disabled></option>');
    dropdown.prop('selectedIndex', 0);

    dropdown2.empty();
    dropdown2.append('<option value="" selected="true" disabled></option>');
    dropdown2.prop('selectedIndex', 0);

    dropdown3.empty();
    dropdown3.append('<option value="" selected="true" disabled></option>');
    dropdown3.prop('selectedIndex', 0);

    const url = "./front/get_finalita.php?struttura=" + strut;
    $.getJSON(url, function (data) {
        $.each(data, function (key, entry) {
            if (tipo_prenotazione == 'ufficiale') {
                if (
                    entry.id != 9062 &&
                    entry.id != 9086 &&
                    entry.id != 9087 &&
                    entry.id != 9088 &&
                    entry.id != 9089 &&
                    entry.id != 9090
                ) {
                    dropdown.append($('<option></option>').attr('value', entry.id).text(entry.descrizione));
                    dropdown2.append($('<option></option>').attr('value', entry.id).text(entry.descrizione));
                }
            }

            if (tipo_prenotazione == 'conoscitivo') {
                if (
                    entry.id != 9081 &&
                    entry.id != 9082 &&
                    entry.id != 9083 &&
                    entry.id != 9084 &&
                    entry.id != 9061 &&
                    entry.id != 411  &&
                    entry.id != 9087 &&
                    entry.id != 9088 &&
                    entry.id != 9089 &&
                    entry.id != 9090
                ) {
                    dropdown.append($('<option></option>').attr('value', entry.id).text(entry.descrizione));
                    dropdown2.append($('<option></option>').attr('value', entry.id).text(entry.descrizione));
                }
            }

            if (tipo_prenotazione == 'chimici') {
                if (
                    entry.id == 9087 ||
                    entry.id == 9088 ||
                    entry.id == 9089 ||
                    entry.id == 9090

                ) {
                    console.log(entry.id)
                    dropdown.append($('<option></option>').attr('value', entry.id).text(entry.descrizione));
                    dropdown3.append($('<option></option>').attr('value', entry.id).text(entry.descrizione));
                }
            }

        })
    });

    $('#ufficiale_finalita').change(function () {
        if ($('#ufficiale_finalita').val() == '9062') {
            $('#unica_istanza').prop('checked', false);
            $('#convocazione_perito').prop('checked', false);
            $('#unica_istanza').prop('disabled', true);
            $('#convocazione_perito').prop('disabled', true);
        } else {
            $('#unica_istanza').prop('checked', true);
            $('#convocazione_perito').prop('checked', true);
            $('#unica_istanza').prop('disabled', false);
            $('#convocazione_perito').prop('disabled', false);
        }
    });
}
function generateCampione() {
    let dropdown = $('#autocontrollo_campione');
    dropdown.empty();
    dropdown.append('<option value="" selected="true" disabled></option>');
    dropdown.prop('selectedIndex', 0);
    const url = "./front/get_campione.php";
    $.getJSON(url, function (data) {
        $.each(data, function (key, entry) {
            dropdown.append($('<option></option>').attr('value', entry.id).text(entry.descrizione));
        })
    });
}
function showLab() {
    let strut = $('#autocontrollo_strutttura option:selected').val();
    let fin = ''
    switch (tipo_prenotazione) {
        case 'conoscitivo':
            fin = $('#conoscitivo_finalita_inline option:selected').val();
            break;
        case 'chimici':
            fin = $('#chimici_finalita option:selected').val();
            break;
        default:
            fin = $('#ufficiale_finalita option:selected').val();
    }
    let campione = $('#autocontrollo_campione option:selected').val();
    let cat = $('#ufficiale_matrice_select option:selected').val();
    const url = "./front/get_lab_by_path.php";
    var prove = [];
    var checkboxes = document.querySelectorAll('input[name="autocontrollo_prove[]"]:checked')
    for (var i = 0; i < checkboxes.length; i++) {
        prove.push(checkboxes[i].value.split('-')[0])
    }
    var data = JSON.stringify({
        strut: strut,
        fin: fin,
        campione: campione,
        prove: prove,
        cat: cat
    });
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        success: function (result) {
            result = JSON.parse(result)
            $('#lab_selected').html(result[0])
            if(tipo_prenotazione != 'chimici'){
                $('#sede_selected').html('Sede di consegna: ' + $('#autocontrollo_strutttura :selected').text());
            }else{
                giorno_prefissato = document.querySelectorAll('input[name="autocontrollo_prove[]"]:checked')[0].dataset.giorno
                $('#sede_selected').html('Sede di consegna: ' + $('#autocontrollo_strutttura :selected').text()+ ' <br/>'+capitalizeFirstLetter(giorno_prefissato));
            }
            lab = result[1];
            slot = moment.utc(moment.duration(+result[2], 'minutes').asMilliseconds()).format("HH:mm:ss")
            slot_partial = +result[2];
            business = result[3];
        },
        error: function (result) {
            console.log(result);
        }
    });

}
function getSlots() {
    const url = "./front/get_lab_info.php?lab=" + lab;
    $.ajax({
        type: 'GET',
        url: url,
        success: function (result) {
            result = JSON.parse(result)
            $('#lab_selected').html(result[0])
            $('#sede_selected').html('Sede di consegna: ' + $('#autocontrollo_strutttura :selected').text());
            slot = moment.utc(moment.duration(+result[2], 'minutes').asMilliseconds()).format("HH:mm:ss")
            slot_partial = +result[2];
            business = result[3];
            calendar.setOption('slotLabelInterval', slot_partial)
            calendar.setOption('slotDuration', slot)
            calendar.setOption('businessHours',
                [{
                    daysOfWeek: [1],
                    startTime: business[1][0],
                    endTime: business[1][1],
                },
                    {
                        daysOfWeek: [2],
                        startTime: business[2][0],
                        endTime: business[2][1],
                    },
                    {
                        daysOfWeek: [3],
                        startTime: business[3][0],
                        endTime: business[3][1],
                    },
                    {
                        daysOfWeek: [4],
                        startTime: business[4][0],
                        endTime: business[4][1],
                    },
                    {
                        daysOfWeek: [5],
                        startTime: business[5][0],
                        endTime: business[5][1],
                    }
                ]);
        },
        error: function (result) {
            console.log(result);
        }
    });

}
function checkpren101(lab, date) {
    const url = "./front/check_pren_10-1.php";
    var data = JSON.stringify({
        date: date,
        lab: lab,
    });
    return $.ajax({
        type: 'POST',
        url: url,
        data: data,
        async: false,
        succes: function (result) {
            return result;
        },
        error: function (result) {
            return result;
        }
    }).responseText;
}
function checkProve(lab, date) {
    let url = ''
    let finalita = $('#autocontrollo_campione').val();
    if (tipo_prenotazione == 'conoscitivo') {
        url = "./front/check_prenotazioni_conoscitive.php";
    }else if (tipo_prenotazione == 'chimici') {
        url = "./front/check_prenotazioni_chimici.php";
    } else {
        url = "./front/check_peso_lab.php";
        if (finalita == '2') {
            url = "./front/check_prenotazioni_autocontrollo_alimenti.php";
        }
    }

    var data = JSON.stringify({
        date: date,
        lab: lab,
        tipo_prenotazione: tipo_prenotazione,
    });
    return $.ajax({
        type: 'POST',
        url: url,
        data: data,
        async: false,
        success: function (result) {
            return result;
        },
        error: function (result) {
            return result;
        }
    }).responseText;
}

function sidebar2() {
    $('#modal_er').modal('show')
}

function generateProva() {
    let dropdown = $('#prove_rows');
    let strut = $('#autocontrollo_strutttura option:selected').val();
    let fin = '';
    switch (tipo_prenotazione) {
        case 'conoscitivo':
            fin = $('#conoscitivo_finalita_inline option:selected').val();
            break;
        case 'chimici':
            fin = $('#chimici_finalita option:selected').val();
            break;
        default:
            fin = $('#ufficiale_finalita option:selected').val();
    }
    let campione = $('#autocontrollo_campione option:selected').val();
    let categoria = $('#ufficiale_matrice_select option:selected').val();
    dropdown.empty();
    const url = "./front/get_prova.php?struttura=" + strut + "&finalita=" + fin + "&campione=" + campione + "&categoria=" + categoria;
    var prove_array = [];
    if($('#autocontrollo_campione :selected').val() == '2'){
        $.getJSON(url, function (data) {
            data.forEach(function (entry) {
                if (prove_array[entry.categoria_prove] == undefined) {
                    prove_array[entry.categoria_prove] = [];
                }
                prove_array[entry.categoria_prove].push(entry);
            })
            for (let key in prove_array) {
                let checkboxes = '';
                if(key == 'undefined' || key == undefined){
                    key_title = 'PROVE';
                }else{
                    key_title = key.replace(/ /g,"_");
                    key_label = key;
                }
                prove_array[key].forEach(function (entry) {
                    checkboxes += `
                    <div class='checkbox'>
                      <input class="form-check-input" data-lab="${entry.id_lab}" type="checkbox" id="autocontrollo_prove_${entry.id}" name="autocontrollo_prove[]" value="${entry.id}-${entry.peso_prova}">
                      <label class="form-check-label" id="autocontrollo_prove_${entry.id}">${entry.descrizione}</label>
                    </div>`;
                })
                dropdown.append($(`
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading${key_title}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${key_title}" aria-expanded="true" aria-controls="collapse${key_title}">
                            ${key_label}
                        </button>
                    </h2>
                    <div id="collapse${key_title}" class="accordion-collapse collapse" aria-labelledby="heading${key_title}" data-bs-parent="#prove_rows">
                        <div class="accordion-body">    
                        ${checkboxes}
                        </div>
                    </div>
                </div>
            `))
            }
        });
    }else{
        $.getJSON(url, function (data) {
            $.each(data, function (key, entry) {
                if(tipo_prenotazione == 'chimici'){
                    dropdown.append($(`
                <div class='checkbox'>
                  <input class="form-check-input" data-giorno="${entry.giorno_prefissato}" data-lab="${entry.id_lab}" type="radio" id="autocontrollo_prove_${entry.id}" name="autocontrollo_prove[]" value="${entry.id}-${entry.peso_prova}">
                  <label class="form-check-label" id="autocontrollo_prove_${entry.id}">${entry.descrizione} - ${entry.giorno_prefissato}</label>
                </div>
                `));
                }else{
                    dropdown.append($(`
                <div class='checkbox'>
                  <input class="form-check-input" data-lab="${entry.id_lab}" type="checkbox" id="autocontrollo_prove_${entry.id}" name="autocontrollo_prove[]" value="${entry.id}-${entry.peso_prova}">
                  <label class="form-check-label" id="autocontrollo_prove_${entry.id}">${entry.descrizione}</label>
                </div>
                `));
                }
            })
        });

    }
}

function generateSpecie() {
    let dropdown = $('#autocontrollo_specie');
    dropdown.empty();
    dropdown.append('<option value="" selected="true" disabled></option>');
    dropdown.prop('selectedIndex', 0);
    const url = "./front/get_specie.php";
    $.getJSON(url, function (data) {
        $.each(data, function (key, entry) {
            dropdown.append($('<option></option>').attr('value', entry.id).text(entry.descrizione));
        })
    });
}

function generateMatrice() {
    $('#autocontrollo_materiale').val('');
    let dropdown = $('#ufficiale_matrice_select');
    let strut = $('#autocontrollo_strutttura option:selected').val();
    let fin = '';
    switch (tipo_prenotazione) {
        case 'conoscitivo':
            fin = $('#conoscitivo_finalita_inline option:selected').val();
            break;
        case 'chimici':
            fin = $('#chimici_finalita option:selected').val();
            break;
        default:
            fin = $('#ufficiale_finalita option:selected').val();
            break;
    }
    let campione = $('#autocontrollo_campione option:selected').val();
    dropdown.empty();
    const url = "./front/get_matrici.php?struttura=" + strut + "&finalita=" + fin + "&campione=" + campione;
    dropdown.append('<option value="" selected="true" disabled></option>');
    dropdown.prop('selectedIndex', 0);
    $.getJSON(url, function (data) {
        $.each(data, function (key, entry) {
            dropdown.append($('<option></option>').attr('value', entry.id).text(entry.descrizione));
        })
    });
}

function generateStrutture() {
    let dropdown = $('#autocontrollo_strutttura');
    dropdown.empty();
    dropdown.append('<option value="" selected="true" disabled> </option>');
    dropdown.prop('selectedIndex', 0);
    const url = "./front/get_struttura.php";
    $.getJSON(url, function (data) {
        strutture = data;
        $.each(data, function (key, entry) {
            dropdown.append($('<option></option>').attr('value', entry.id).text(entry.descrizione));
            if (tipo_prenotazione == 'autocontrollo') {
                //esco se autocontrollo, in modo da avere solo la sede di Brescia
                return false;

            }
        })
    });
}
function generateMap() {
    accessToken = 'pk.eyJ1Ijoicmthc3NhbWUiLCJhIjoiY2t6bWk1ZGZ6MDdmMzJvbzAxOXFmYzE4MCJ9.lMccLYVfyS3YvRuhqTXnXg';
    if (map != null) {
        map.remove();
    }
    map = L.map('map', { zoomControl: false }).setView([45.5357589, 10.2123235], 8);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        maxZoom: 18,
        id: 'mapbox/light-v10',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: accessToken
    }).addTo(map);
    const url = "./front/get_struttura.php";
    var icona = new L.Icon({
        iconUrl: 'assets/images/marker-mappa.png',
        iconAnchor: [17.5, 25]
    });

    $.getJSON(url, function (data) {
        $.each(data, function (key, entry) {
            L.marker(entry.coordinate.split(','), { icon: icona }).addTo(map).on('click', function (e) {
                let id = entry.id;
                $('#autocontrollo_strutttura').val(id.toString())
                $('#autocontrollo_strutttura').select2({
                    theme: "bootstrap",
                    width: '100%'
                })
                map.setView(entry.coordinate.split(','), 13);
            });
            if (tipo_prenotazione == 'autocontrollo') {
                //esco se autocontrollo, in modo da avere solo la sede di Brescia
                return false;

            }
        })

    });
}
function checkEventiByDate(lab, tmp_date, end) {
    return $.ajax({
        async: false,
        type: 'GET',
        url: './assets/lib/check_eventi_by_date.php?lab=' + lab + '&date=' + tmp_date + '&end_date=' + end,
        success: function (result) {
            return result;
        }
    }).responseText;

}
function sidebar() {
    $('#modal2').modal('show')
}

function riepilogo_sidebar() {
    var stuff = [];
    var sede = $('#autocontrollo_strutttura :selected').text();
    var finalita;
    var matrice;
    var campione;
    var n_campione;
    var specie;
    var all_case;
    var prove = [];
    var selected = $('#lab_selected').html()
    prenot = $('#date_prenotazione').val()
    if (prenot) {
        prenot = moment(prenot).format('DD/MM/Y [h] hh.mm')
    }
    $('input[name="autocontrollo_prove[]"]:checked').serializeArray().forEach((element) => {
        id = parseInt(element.value.split('-')[0]);
        prove.push($('#autocontrollo_prove_' + id)[0].nextElementSibling.textContent)
    })
    if (tipo_prenotazione == 'ufficiale') {
        finalita = $('#ufficiale_finalita :selected').text()
        categoria_matrice = $('#ufficiale_matrice_select :selected').text()
        matrice = $('#autocontrollo_materiale').val()
        convocazione_perito = $('#convocazione_perito').is(":checked")
        unica_istanza = $('#unica_istanza').is(":checked")
        if (convocazione_perito) {
            convocazione_perito = 'Si'
        } else {
            convocazione_perito = 'No'
        }

        if (unica_istanza) {
            unica_istanza = 'Si'
        } else {
            unica_istanza = 'No'
        }
        var data = `
            <p>Tipo prenotazione: <span>${tipo_prenotazione ?? ''}</span></p> 
            <p>Sede di consegna: <span>${sede ?? ''} </span></p>
            <p>Finalità: <span>${finalita ?? ''}</span></p>
            <p>Convocazione perito: <span>${convocazione_perito ?? ''}</span></p>
            <p>Unica istanza: <span>${unica_istanza ?? ''}</span></p>
            <p>Categoria: <span>${categoria_matrice ?? ''}</span></p>
            <p>Matrice: <span> ${matrice ?? ''}</span></p>
            <p>Prova/e: <span>${prove.join(", ") ?? ''}</span></p>
            <p>Data di prenotazione: <span> ${prenot ?? ''}</span></p>
            <p>Laboratorio: <span>${selected ?? ''}</span></p>
        `

        $('#riepilogo_data').html(data);
    } else if (tipo_prenotazione == 'conoscitivo') {
        finalita = $('#conoscitivo_finalita_inline :selected').text()
        categoria_matrice = $('#ufficiale_matrice_select :selected').text()
        matrice = $('#autocontrollo_materiale').val()
        prenot = moment($('#date_prenotazione').val()).format('DD/MM/Y [h]') + ' 00:00'
        var data = `
            <p>Tipo prenotazione: <span>${tipo_prenotazione ?? ''}</span></p> 
            <p>Sede di consegna: <span>${sede ?? ''} </span></p>
            <p>Finalità: <span>${finalita ?? ''}</span></p>
            <p>Categoria: <span>${categoria_matrice ?? ''}</span></p>
            <p>Matrice: <span> ${matrice ?? ''}</span></p>
            <p>Prova/e: <span>${prove.join(", ") ?? ''}</span></p>
            <p>Data di prenotazione: <span> ${prenot ?? ''}</span></p>
            <p>Laboratorio: <span>${selected ?? ''}</span></p>
        `

        $('#riepilogo_data').html(data);
    }else if(tipo_prenotazione == 'chimici') {
        all_case = $('#note_chimici').val()
        finalita = $('#chimici_finalita :selected').text()
        categoria_matrice = $('#ufficiale_matrice_select :selected').text()
        matrice = $('#autocontrollo_materiale').val()
        convocazione_perito = $('#convocazione_perito').is(":checked")
        unica_istanza = $('#unica_istanza').is(":checked")
        if (convocazione_perito) {
            convocazione_perito = 'Si'
        } else {
            convocazione_perito = 'No'
        }

        if (unica_istanza) {
            unica_istanza = 'Si'
        } else {
            unica_istanza = 'No'
        }
        var data = `
            <p>Tipo prenotazione: <span>${tipo_prenotazione ?? ''}</span></p> 
            <p>Sede di consegna: <span>${sede ?? ''} </span></p>
            <p>Finalità: <span>${finalita ?? ''}</span></p>
            <p>Convocazione perito: <span>${convocazione_perito ?? ''}</span></p>
            <p>Unica istanza: <span>${unica_istanza ?? ''}</span></p>
            <p>Categoria: <span>${categoria_matrice ?? ''}</span></p>
            <p>Note: <span>${all_case}</span></p>
            <p>Prova/e: <span>${prove.join(", ") ?? ''}</span></p>
            <p>Data di prenotazione: <span> ${prenot ?? ''}</span></p>
            <p>Laboratorio: <span>${selected ?? ''}</span></p>
        `
        $('#riepilogo_data').html(data);
    } else {
        campione = $('#autocontrollo_campione :selected').text()
        n_campione = $('#autocontrollo_n_campione').val()
        specie = $('#autocontrollo_specie :selected').text()
        all_case = $('#all_case').val()
        matrice = $('#autocontrollo_matrice_materiale').val()
        if ($('#autocontrollo_campione :selected').val() == '2') {
            var data = `
            <p>Tipo prenotazione: <span>${tipo_prenotazione ?? ''}</span></p>
            <p>Sede di consegna: <span>${sede ?? ''}</span></p>
            <p>Campione: <span>${campione ?? ''}</span></p>
            <p>Numero campioni: <span>${n_campione ?? ''}</span></p>
            <p>Note: <span>${all_case ?? ''}</span></p>
            <p>Matrice/materiale: <span>${matrice ?? ''}</span></p>
            <p>Prova/e: <span>${prove.join(", ") ?? ''}</span></p>
            <p>Data di prenotazione: <span> ${prenot ?? ''}</span></p>
            <p>Laboratorio: <span>${selected ?? ''}</span></p>
        `
        } else {
            var data = `
            <p>Tipo prenotazione: <span>${tipo_prenotazione ?? ''}</span></p>
            <p>Sede di consegna: <span>${sede ?? ''}</span></p>
            <p>Campione: <span>${campione ?? ''}</span></p>
            <p>Numero campioni: <span>${n_campione ?? ''}</span></p>
            <p>Specie: <span>${specie ?? ''}</span></p>
            <p>Allevamento/caseificio dei campioni: <span>${all_case ?? ''}</span></p>
            <p>Prova/e: <span>${prove.join(", ") ?? ''}</span></p>
            <p>Data di prenotazione: <span> ${prenot ?? ''}</span></p>
            <p>Laboratorio: <span>${selected ?? ''}</span></p>
        `
        }
        $('#riepilogo_data').html(data);
    }
    $('#modal').modal('show')
    $('#close').click(function () {
        $('#modal').modal('hide')
    })
}
function riepilogo() {
    var stuff = [];
    var sede = $('#autocontrollo_strutttura :selected').text();
    var finalita;
    var matrice;
    var campione;
    var n_campione;
    var specie;
    var all_case;
    var prove = [];
    prenot = $('#date_prenotazione').val()
    var selected = $('#lab_selected').html()
    prenot = moment(prenot).format('DD/MM/Y [h] hh.mm')
    $('input[name="autocontrollo_prove[]"]:checked').serializeArray().forEach((element) => {
        id = parseInt(element.value.split('-')[0]);
        prove.push($('#autocontrollo_prove_' + id)[0].nextElementSibling.textContent)
    })
    if (tipo_prenotazione == 'ufficiale') {
        finalita = $('#ufficiale_finalita :selected').text()
        categoria_matrice = $('#ufficiale_matrice_select :selected').text()
        matrice = $('#autocontrollo_materiale').val()
        convocazione_perito = $('#convocazione_perito').is(":checked")
        unica_istanza = $('#unica_istanza').is(":checked")
        if (convocazione_perito) {
            convocazione_perito = 'Si'
        } else {
            convocazione_perito = 'No'
        }

        if (unica_istanza) {
            unica_istanza = 'Si'
        } else {
            unica_istanza = 'No'
        }

        $('#riepilogo').html(`
            <p>Tipo prenotazione: <span>${tipo_prenotazione}</span></p> 
            <p>Sede di consegna: <span>${sede} </span></p>
            <p>Finalità: <span>${finalita}</span></p>
            <p>Convocazione perito: <span>${convocazione_perito}</span></p>
            <p>Unica istanza: <span>${unica_istanza}</span></p>
            <p>Categoria: <span>${categoria_matrice}</span></p>
            <p>Matrice: <span> ${matrice}</span></p>
            <p>Prova/e: <span>${prove.join(", ")}</span></p>
            <p>Data di prenotazione: <span> ${prenot}</span></p>
            <p>Laboratorio: <span>${selected ?? ''}</span></p>
            <br>
            Per cancellare la prenotazione effettuata è necessario chiamare il laboratorio.
        `);
    }else if (tipo_prenotazione == 'chimici') {
        all_case = $('#note_chimici').val()
        prenot = moment($('#date_prenotazione').val()).format('DD/MM/Y [h]') + ' 00:00'
        finalita = $('#chimici_finalita :selected').text()
        categoria_matrice = $('#ufficiale_matrice_select :selected').text()
        matrice = $('#autocontrollo_materiale').val()
        convocazione_perito = $('#convocazione_perito').is(":checked")
        unica_istanza = $('#unica_istanza').is(":checked")
        if (convocazione_perito) {
            convocazione_perito = 'Si'
        } else {
            convocazione_perito = 'No'
        }

        if (unica_istanza) {
            unica_istanza = 'Si'
        } else {
            unica_istanza = 'No'
        }

        $('#riepilogo').html(`
            <p>Tipo prenotazione: <span>${tipo_prenotazione}</span></p> 
            <p>Sede di consegna: <span>${sede} </span></p>
            <p>Finalità: <span>${finalita}</span></p>
            <p>Convocazione perito: <span>${convocazione_perito}</span></p>
            <p>Unica istanza: <span>${unica_istanza}</span></p>
            <p>Categoria: <span>${categoria_matrice}</span></p>
            <p>Note: <span>${all_case}</span></p>
            <p>Prova/e: <span>${prove.join(", ")}</span></p>
            <p>Data di prenotazione: <span> ${prenot}</span></p>
            <p>Laboratorio: <span>${selected ?? ''}</span></p>
            <br>
            Per cancellare la prenotazione effettuata è necessario chiamare il laboratorio.
        `);
    } else if (tipo_prenotazione == 'conoscitivo') {
        prenot = moment($('#date_prenotazione').val()).format('DD/MM/Y [h]') + ' 00:00'
        finalita = $('#conoscitivo_finalita_inline :selected').text()
        categoria_matrice = $('#ufficiale_matrice_select :selected').text()
        matrice = $('#autocontrollo_materiale').val()
        convocazione_perito = $('#convocazione_perito').is(":checked")
        unica_istanza = $('#unica_istanza').is(":checked")


        $('#riepilogo').html(`
            <p>Tipo prenotazione: <span>${tipo_prenotazione}</span></p> 
            <p>Sede di consegna: <span>${sede} </span></p>
            <p>Finalità: <span>${finalita}</span></p>
            <p>Categoria: <span>${categoria_matrice}</span></p>
            <p>Matrice: <span> ${matrice}</span></p>
            <p>Prova/e: <span>${prove.join(", ")}</span></p>
            <p>Data di prenotazione: <span> ${prenot}</span></p>
            <p>Laboratorio: <span>${selected ?? ''}</span></p>
            <br>
            Per cancellare la prenotazione effettuata è necessario chiamare il laboratorio.
        `);
    } else {
        campione = $('#autocontrollo_campione :selected').text()
        n_campione = $('#autocontrollo_n_campione').val()
        specie = $('#autocontrollo_specie :selected').text()
        all_case = $('#all_case').val()
        matrice = $('#autocontrollo_matrice_materiale').val()
        if ($('#autocontrollo_campione :selected').val() == '2') {
            $('#riepilogo').html(`
            <p>Tipo prenotazione: <span>${tipo_prenotazione}</span></p>
            <p>Sede di consegna: <span>${sede}</span></p>
            <p>Campione: <span>${campione}</span></p>
            <p>Numero campioni: <span>${n_campione}</span></p>
            <p>Specie: <span>${specie}</span></p>
            <p>Note: <span>${all_case}</span></p>
            <p>Matrice/materiale: <span>${matrice ?? ''}</span></p>
            <p>Prova/e: <span>${prove.join(", ")}</span></p>
            <p>Data di prenotazione: <span> ${prenot}</span></p>
            <p>Laboratorio: <span>${selected ?? ''}</span></p>
            <br>
            Per cancellare la prenotazione effettuata è necessario chiamare il laboratorio.
        `);
        } else {
            $('#riepilogo').html(`
            <p>Tipo prenotazione: <span>${tipo_prenotazione}</span></p>
            <p>Sede di consegna: <span>${sede}</span></p>
            <p>Campione: <span>${campione}</span></p>
            <p>Numero campioni: <span>${n_campione}</span></p>
            <p>Specie: <span>${specie}</span></p>
            <p>Allevamento/caseificio dei campioni: <span>${all_case}</span></p>
            <p>Prova/e: <span>${prove.join(", ")}</span></p>
            <p>Data di prenotazione: <span> ${prenot}</span></p>
            <p>Laboratorio: <span>${selected ?? ''}</span></p>
            <br>
            Per cancellare la prenotazione effettuata è necessario chiamare il laboratorio.
        `);
        }

    }
}
$(document).ready(function () {
    $('#autocontrollo_strutttura').select2({
        theme: "bootstrap",
        width: '100%'
    })
    $('#ufficiale_finalita').select2({
        theme: "bootstrap",
        width: '100%'
    })
    $('#chimici_finalita').select2({
        theme: "bootstrap",
        width: '100%'
    })
    $('#conoscitivo_finalita_inline').select2({
        theme: "bootstrap",
        width: '100%'
    })
    $('#ufficiale_matrice_select').select2({
        theme: "bootstrap",
        width: '100%'
    })
    $('#autocontrollo_campione').select2({
        theme: "bootstrap",
        width: '100%'
    })
    $('#autocontrollo_specie').select2({
        theme: "bootstrap",
        width: '100%'
    })
    $('#autocontrollo_campione').on('select2:select', function (e) {
        let val = e.params.data.id;
        if (val == 2) {
            $('#microbiologia_alimenti').css('display', 'block')
            $('#default_autocontrollo').css('display', 'none')
        } else {
            $('#microbiologia_alimenti').css('display', 'none')
            $('#default_autocontrollo').css('display', 'block')
        }
    });

    $('#autocontrollo_strutttura').on('select2:select', function (e) {
        let val = e.params.data.id;
        let coordinate = strutture.find(({ id }) => id === val).coordinate.split(',');
        map.setView(coordinate, 13);
    });
    document.getElementById('tipo_prenotazione').value = tipo_prenotazione
    var current_fs, next_fs, previous_fs;
    var opacity;

    $(".next").click(function (el) {
        current_fs = $(this).parent();
        next_fs = $(this).parent().next();
        if ($('#autocontrollo_strutttura').val() == null && el.target.id == 'second') {
            alertify.alert('Attenzione', 'Attenzione, inserire una sede di consegna valida', function () { });
            return;
        }
        if (tipo_prenotazione == 'ufficiale') {
            if ($('#ufficiale_finalita').val() == null && el.target.id == 'third') {
                alertify.alert('Attenzione', 'Attenzione, inserire una finalità valida', function () { });
                return;
            }
        }
        if (tipo_prenotazione == 'conoscitivo') {
            if ($('#conoscitivo_finalita_inline').val() == null && el.target.id == 'third') {
                alertify.alert('Attenzione', 'Attenzione, inserire una finalità valida', function () { });
                return;
            }
        }
        if (tipo_prenotazione == 'autocontrollo') {
            if (($('#autocontrollo_campione').val() == null || $('#autocontrollo_n_campione').val() == null || $('#autocontrollo_n_campione').val() == '' || ($('#autocontrollo_specie').val() == null) && $('#autocontrollo_campione :selected').val() != '2') && el.target.id == 'third') {
                alertify.alert('Attenzione', 'Attenzione, inserire le informazioni riguardo i campioni', function () { });
                return;
            }
            if (($('#autocontrollo_campione').val() == null || $('#autocontrollo_n_campione').val() == null || $('#autocontrollo_matrice_materiale').val() == '' && $('#autocontrollo_campione :selected').val() == '2') && el.target.id == 'third') {
                alertify.alert('Attenzione', 'Attenzione, inserire le informazioni riguardo i campioni', function () { });
                return;
            }
        }
        if (($('#autocontrollo_materiale').val() == '' || $('#ufficiale_matrice_select').val() == null) && tipo_prenotazione == 'ufficiale' && el.target.id == 'fourth') {
            alertify.alert('Attenzione', 'Attenzione, inserire le informazioni mancanti', function () { });
            return;
        }
        if (($('#autocontrollo_materiale').val() == '' || $('#ufficiale_matrice_select').val() == null) && tipo_prenotazione == 'conoscitivo' && el.target.id == 'fourth') {
            alertify.alert('Attenzione', 'Attenzione, inserire le informazioni mancanti', function () { });
            return;
        }

        if (tipo_prenotazione == 'autocontrollo' && el.target.id == 'third') {
            $('#ufficiale_matrice_select').val(0)
            $('#autocontrollo_materiale').val('-');
            setTimeout(function () {
                $('#fourth').click()
            }, 300);

        }
        if (tipo_prenotazione == 'chimici' && el.target.id == 'third') {
            $('#ufficiale_matrice_select').val(0)
            $('#autocontrollo_materiale').val('-');
            setTimeout(function () {
                $('#fourth').click()
            }, 300);

        }
        var checkkk = Array.from($('input[name="autocontrollo_prove[]"]:checked')).map(input => input.dataset.lab).every((val, i, arr) => val === arr[0])
        if ($('input[name="autocontrollo_prove[]"]:checked').length == 0 && el.target.id == 'fifth') {
            alertify.alert('Attenzione', 'Attenzione, inserire almeno una prova', function () { });
            return;
        }
        if (!checkkk && el.target.id == 'fifth') {
            alertify.alert('Attenzione', 'Attenzione, le prove non sono associabili,è necessario effettuare prenotazioni distinte.', function () { });
            return;
        }
        if (el.target.id == 'sixth') {
            if (document.getElementById('date_prenotazione').value != '') {
                riepilogo()
            } else {
                alertify.alert('Attenzione', 'Attenzione, selezionare uno slot', function () { });
                return;
            }
        }
        //Add Class Active

        $("#progressbar #" + tipo_prenotazione + " li").eq($("fieldset").index(next_fs)).addClass("active");

        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({
            opacity: 0
        }, {
            step: function (now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                next_fs.css({
                    'opacity': opacity
                });
            },
            duration: 400
        });
    });
    $(".end").click(function () {
        current_fs = $(this).parent();
        next_fs = $(this).parent().next();

        if (document.getElementById('date_prenotazione').value === '') {
            alertify.alert('Attenzione', 'Attenzione, è necessario scegliere un giorno per continuare la prenotazione.', function () { });
            return;
        }
        alertify.confirm("Sei sicuro/a di voler completare la prenotazione?", function () {
            var form = $("#msform").serialize()
            var date_tmp = document.getElementById('date_prenotazione').value;
            if (tipo_prenotazione == 'ufficiale') {
                var stat = checkEventiByDate(lab, date_tmp);
                if (stat === 'false') {
                    alertify.alert('attenzione', 'Attenzione, è stato superato il numero massimo di prenotazioni per il ' + moment(date_tmp).format('DD/MM/YYYY [alle] hh:mm') + '. Scegliere un’altro slot.', function () { });
                    return false;
                }
            }
            $.ajax({
                type: 'POST',
                url: './front/savePrenotazione.php?lab=' + lab,
                data: form,
                success: function (result) {
                    $.LoadingOverlay("hide")
                    result = JSON.parse(result);
                    $('#return_content').html(result.html)
                    order = result.order;
                    $("#progressbar #" + tipo_prenotazione + " li").eq($("fieldset").index(next_fs)).addClass("active");

                    //show the next fieldset
                    next_fs.show();
                    //hide the current fieldset with style
                    current_fs.animate({
                        opacity: 0
                    }, {
                        step: function (now) {
                            // for making fielset appear animation
                            opacity = 1 - now;

                            current_fs.css({
                                'display': 'none',
                                'position': 'relative'
                            });
                            next_fs.css({
                                'opacity': opacity
                            });
                        },
                        duration: 400
                    });
                },
                beforeSend: function () {
                    $.LoadingOverlay("show")
                },
                error: function (result) {
                    $.LoadingOverlay("hide")
                    alertify.alert('Attenzione', 'Attenzione, si è verificato un errore nella creazione della prenotazione, riprovare.', function () { });
                    return;

                }
            });

        }).set('labels', {
            ok: 'Conferma',
            cancel: 'Annulla'
        });
    });

    $(".previous").click(function (el) {

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //Remove class active
        $("#progressbar #" + tipo_prenotazione + " li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();

        //hide the current fieldset with style
        current_fs.animate({
            opacity: 0
        }, {
            step: function (now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                previous_fs.css({
                    'opacity': opacity
                });
            },
            duration: 400
        });
        if ((tipo_prenotazione == 'autocontrollo' || tipo_prenotazione == 'chimici') && el.target.id == 'fifth_back') {
            setTimeout(function () {
                $('#fourth_back').click()
            }, 300);
        }
    });

    $('.radio-group .radio').click(function () {
        document.getElementById("msform").reset()
        $(this).parent().find('.radio').removeClass('selected');
        $(this).addClass('selected');
        tipo_prenotazione = $(this).data('value');
        document.getElementById('tipo_prenotazione').value = tipo_prenotazione
        if (tipo_prenotazione == 'ufficiale') {
            document.getElementById('ufficiale').style.display = 'block'
            document.getElementById('ufficiale_finalita_form').style.display = 'block'
            document.getElementById('ufficiale_campioni_form').style.display = 'none'
            document.getElementById('autocontrollo').style.display = 'none'
            document.getElementById('conoscitivo_finalita_form').style.display = 'none'
            document.getElementById('conoscitivo').style.display = 'none'
            document.getElementById('chimici_finalita_form').style.display = 'none'
            document.getElementById('chimici').style.display = 'none'
            document.getElementById('autocontrollo_materiale').style.display = 'block'
            document.getElementById('autocontrollo_materiale_label').style.display = 'block'
        }
        if (tipo_prenotazione == 'autocontrollo') {
            document.getElementById('ufficiale').style.display = 'none'
            document.getElementById('ufficiale_finalita_form').style.display = 'none'
            document.getElementById('ufficiale_campioni_form').style.display = 'block'
            document.getElementById('autocontrollo').style.display = 'block'
            document.getElementById('conoscitivo_finalita_form').style.display = 'none'
            document.getElementById('conoscitivo').style.display = 'none'
            document.getElementById('chimici_finalita_form').style.display = 'none'
            document.getElementById('chimici').style.display = 'none'
            document.getElementById('autocontrollo_materiale').style.display = 'block'
            document.getElementById('autocontrollo_materiale_label').style.display = 'block'
        }
        if (tipo_prenotazione == 'conoscitivo') {
            document.getElementById('ufficiale').style.display = 'none'
            document.getElementById('ufficiale_finalita_form').style.display = 'none'
            document.getElementById('ufficiale_campioni_form').style.display = 'none'
            document.getElementById('autocontrollo').style.display = 'none'
            document.getElementById('conoscitivo_finalita_form').style.display = 'block'
            document.getElementById('conoscitivo').style.display = 'block'
            document.getElementById('chimici_finalita_form').style.display = 'none'
            document.getElementById('chimici').style.display = 'none'
            document.getElementById('autocontrollo_materiale').style.display = 'block'
            document.getElementById('autocontrollo_materiale_label').style.display = 'block'
        }
        if (tipo_prenotazione == 'chimici') {
            document.getElementById('ufficiale').style.display = 'none'
            document.getElementById('ufficiale_finalita_form').style.display = 'none'
            document.getElementById('ufficiale_campioni_form').style.display = 'none'
            document.getElementById('autocontrollo').style.display = 'none'
            document.getElementById('conoscitivo_finalita_form').style.display = 'none'
            document.getElementById('conoscitivo').style.display = 'none'
            document.getElementById('chimici_finalita_form').style.display = 'block'
            document.getElementById('chimici').style.display = 'block'
            document.getElementById('autocontrollo_materiale').style.display = 'none'
            document.getElementById('autocontrollo_materiale_label').style.display = 'none'
        }
        $('#first').click()
        setTimeout(function () {
            generateMap()
        }, 300)
    });

    $(".submit").click(function () {
        return false;
    })
});

function reload() {
    window.location.href = "/form.php";
}

function exit() {
    window.location.href = "/index.php";
}
function back() {
    $('.previous')[5].click()
    $("#concluded").css({
        'display': 'none',
        'position': 'relative'
    });
    var animationDuration = 200;
    setTimeout(function () {
        document.getElementById('date_prenotazione').value = '';
        generateCalendar()
    }, animationDuration);
}

function remove() {
    alertify.confirm("Sei sicuro/a di voler eliminare la prenotazione " + order + "?", function () {
        var del = true;
        var form = $("#msform").serialize()
        $.ajax({
            type: 'POST',
            url: './front/savePrenotazione.php?lab=' + lab,
            data: {
                form: form,
                del: del,
                id_d: order
            },

            success: function () {
                $.ajax({
                    type: "POST",
                    data: {
                        id: order,
                        delete_booking: 1
                    },
                    url: "./assets/lib/my_appoint_ajax.php",
                    success: function (response) {
                        location.reload();
                    }
                });
            },
            error: function (result) {

            }
        });

    }).set('labels', {
        ok: 'Conferma',
        cancel: 'Annulla'
    });

}

function esci() {
    alertify.confirm("Sei sicuro di voler uscire?", function () {
        $.ajax({
            type: "POST",
            url: "./assets/lib/admin_login_ajax.php",
            data: { logout: 1 },
            success: function (response) {
                window.location.href = "/";
            }
        });
    });
}
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}