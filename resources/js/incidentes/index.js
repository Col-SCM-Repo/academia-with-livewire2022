import axios from "axios";
import { data } from "jquery";
import Vue from "vue";

var indexVue = new Vue({
    el: "#app",
    data: {
        baseUrl: $("#baseUrl").val() + "incidentes",
        idMatricula: $("#matricula_id").val(),
        data: null,
        modal: "",
        justificado: false,
        edicion: false,
    },
    methods: {
        descargarReporteIncidencias: function (event) {
            event.preventDefault();
            console.log(event);
            const $form = event.target;

            //return 0;

            //ValidarFormulario

            console.log(
                "$form.todoPeriodo.checked - ",
                document.getElementById("todo-periodo").checked
            );
            const data = new FormData();
            data.append("id_matricula", this.idMatricula);
            data.append("fecha_inicio", $form.fInicio.value);
            data.append("fecha_fin", $form.fFin.value);

            data.append("filtro_todos", $form.cbxTodos.checked);
            data.append("filtro_inasistencia", $form.cbxInasistencia.checked);
            data.append("filtro_tardanza", $form.cbxTardanza.checked);
            data.append(
                "filtro_comportamiento",
                $form.cbxComportamiento.checked
            );
            data.append("filtro_notas", $form.cbxNotas.checked);

            data.append("todo_periodo", $form.todoPeriodo.checked);

            const url = event.target.getAttribute("action");
            toastr.success("Descargando", "Abriendo reporte...");
            axios(url, { data, method: "POST", responseType: "blob" })
                .then((response) => {
                    console.log(response.data);

                    const blob = new Blob([response.data], {
                        type: "application/pdf",
                    });
                    let objectUrl = URL.createObjectURL(blob);
                    window.open(objectUrl);
                    /**
                    let link = document.createElement("a");
                    let fname = `Reporte especifico`; // El nombre del archivo descargado
                    link.href = objectUrl;
                    link.setAttribute("download", fname);
                    document.body.appendChild(link);
                    link.click();
                    */
                })
                .catch((err) => {
                    console.error(err);
                    toastr.error("Error al cargar incidentes", "Incidentes.");
                });
        },
        cargarData: function () {
            let url = this.baseUrl + "/info-incidentes/" + this.idMatricula;
            this.limpiarFormularioIncidentes();
            axios(url)
                .then((response) => {
                    this.data = response.data;
                })
                .catch((err) => {
                    console.warn(err);
                    toastr.error("Error al cargar incidentes", "Incidentes.");
                });
        },
        cargarEvidencias: function ($id_incidente) {
            this.temp_evidencias = [];

            const bindCargarEvidencias = this.cargarEvidencias.bind(this);
            const url = $("#baseUrl").val() + "evidencias";
            axios(url + "/" + $id_incidente)
                .then((response) => {
                    document.getElementById("container-evidencias").innerHTML =
                        response.data;
                    for (const item of document.querySelectorAll(
                        ".btn-eliminar-evidencia"
                    )) {
                        item.addEventListener("click", (event) => {
                            event.preventDefault();
                            swal(
                                {
                                    title: "¿Ésta seguro de eliminar la evidencia?",
                                    text: "Si selecciona aceptar, se eliminara completamente la evidencia del registro",
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#F8C786",
                                    cancelButtonColor: "#d33",
                                    confirmButtonText: "Si, Eliminar",
                                    cancelButtonText: "Cancelar",
                                    closeOnConfirm: true,
                                },
                                function () {
                                    //const data = new FormData(event.target);/*/*/*/*************************************** */
                                    //console.log(event.target);
                                    const url =
                                        $("#baseUrl").val() +
                                        "evidencias/" +
                                        event.target.dataset.target;
                                    axios({
                                        method: "DELETE",
                                        responseType: "json",
                                        data,
                                        url,
                                    })
                                        .then((response) => {
                                            bindCargarEvidencias($id_incidente);
                                            toastr.success(
                                                "Evidencia eliminada correctamente",
                                                "Evidencias."
                                            );
                                        })
                                        .catch((e) => {
                                            console.warn(e);
                                            toastr.error(
                                                "Error al cargar evidencias",
                                                "Evidencias."
                                            );
                                        });
                                }
                            );
                        });
                        //console.log(item);
                    }
                })
                .catch((err) => console.error(err));
        },

        submitIncidente: function ($event) {
            const bindCargarData = this.cargarData.bind(this);
            $event.preventDefault();
            if (this.validacionFormIncidentes($event.target)) {
                const data = new FormData($event.target);
                data.append("id_enrollment", this.idMatricula);
                let url = $event.target.getAttribute("action");

                if (this.edicion) {
                    url += "/" + $event.target.id_incidente.value;
                    axios({
                        method: "POST",
                        responseType: "json",
                        data,
                        url,
                    })
                        .then((response) => {
                            $("#incidentes-modal").modal("hide");
                            bindCargarData();
                            toastr.success(
                                " Incidente  actualizado correctamente.",
                                "Incidentes"
                            );
                        })
                        .catch((e) => {
                            toastr.error(
                                "Error al actualizar Incidente",
                                "Error."
                            );
                            console.error(e);
                        });
                } else
                    axios({
                        method: "POST",
                        responseType: "json",
                        data,
                        url,
                    })
                        .then((response) => {
                            $("#incidentes-modal").modal("hide");
                            bindCargarData();
                            toastr.success(
                                " Incidente  registrado correctamente.",
                                "Incidentes"
                            );
                        })
                        .catch((e) => {
                            toastr.error(
                                "Error al registrar Incidente",
                                "Error."
                            );
                            console.error(e);
                        });
            }
        },
        onSubmitEnvidencias: function (event) {
            event.preventDefault();
            const $form = event.target;

            if (this.validacionFormEvidencias($form)) {
                const data = new FormData($form);
                data.append("id_incidente", $form.id_incidente.value);
                data.append("descripcion", $form.descripcion.value);
                data.append("file", $form.file_evidencia.value);

                const url = event.target.getAttribute("action");
                const bindCargarEvidencias = this.cargarEvidencias.bind(this);
                const bindClear = this.limpiarFormularioEvidencias.bind(this);

                axios(url, {
                    method: "POST",
                    data,
                    headers: { "Content-Type": "multipart/form-data" },
                })
                    .then((response) => {
                        toastr.success(
                            "Evidencia cargada correctamente",
                            " Evidencias."
                        );
                        bindClear();
                        bindCargarEvidencias($form.id_incidente.value);
                    })
                    .catch((err) => {
                        console.error(err);
                        toastr.error(
                            "Error al cargar evidencia",
                            " Evidencias."
                        );
                    });
            }
        },

        onclickIncidente: function (id_incidente) {
            //Modal, Modo edicion
            this.limpiarFormularioIncidentes();
            $("#incidentes-modal").modal("show");

            //Cargamos data en el modal
            if (data) {
                const incidente = this.data.incidentes.find(
                    (inc) => inc.id == id_incidente
                );
                if (incidente) {
                    this.edicion = true;
                    this.justificado = false;

                    const $form = document.getElementById("modal-incidentes");
                    $form.tipo_incidente.value = incidente.tipo_incidente;
                    $form.justificacion_estado.value = incidente.estado;
                    $form.text_incidente.value = incidente.descripcion;

                    if (this.edicion) $form.id_incidente.value = id_incidente;

                    if (incidente.estado == "1") {
                        this.justificado = true;
                        $form.parentesco.value = incidente.parentesco;
                        $form.text_justificacion.value =
                            incidente.justificacion;
                    }
                } else {
                    toastr.warning(
                        "Error al encontrar a incidente.",
                        "Editar Incidente"
                    );
                }
            }

            this.edicion = true;
        },
        clickBtnEnvidencias: function (id_incidente) {
            this.cargarEvidencias(id_incidente);
            //temp_evidencias
            $("#evidencias-modal").modal("show");
            document.getElementById("form-evidencias").id_incidente.value =
                id_incidente;
        },
        clickBtnNuevoIncidente: function () {
            this.justificado = true;
            this.limpiarFormularioIncidentes();
            this.justificado = false;
            this.edicion = false;
            $("#incidentes-modal").modal("show");
        },

        validacionFormIncidentes: function ($form) {
            // Validando datos minimos (tipo incidente, estado y descripcion)
            if (
                $form.tipo_incidente.value != "-" &&
                $form.text_incidente.value.trim().length != 0
            ) {
                // Validando campos de justificacion
                if ($form.justificacion_estado.value == "1") {
                    if (
                        $form.parentesco.value == "-" ||
                        $form.text_justificacion.value.trim().length == 0
                    ) {
                        toastr.error(
                            "Faltan campos de justificación.",
                            "Incidentes"
                        );
                        return false;
                    }
                }
                return true;
            }
            toastr.error(
                "Faltan campos para describir el incidente.",
                "Incidentes"
            );
            return false;
        },
        validacionFormEvidencias: function ($form) {
            // Validando datos minimos (tipo incidente, estado y descripcion)
            if (
                $form.file_evidencia.files.length > 0 &&
                $form.descripcion.value.trim().length != 0
            ) {
                return true;
            }
            toastr.warning(
                "Faltan campos para describir el incidente.",
                "Incidentes"
            );
            return false;
        },

        onClickEliminarIncidente: function () {
            //  id_incidente
            const id_incidente =
                document.getElementById("modal-incidentes").id_incidente.value;
            console.log(id_incidente);

            const bindCargarData = this.cargarData.bind(this);

            swal(
                {
                    title: "¿Ésta seguro de eliminar el incidente?",
                    text: "Si selecciona aceptar, se eliminara completamente el incidente del registro",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#F8C786",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, Eliminar",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: true,
                },
                function () {
                    //const data = new FormData(event.target);/*/*/*/*************************************** */
                    //console.log(event.target);
                    const url =
                        $("#baseUrl").val() + "incidentes/" + id_incidente;
                    axios({
                        method: "DELETE",
                        responseType: "json",
                        url,
                    })
                        .then((response) => {
                            bindCargarData(id_incidente);
                            $("#incidentes-modal").modal("hide");

                            toastr.success(
                                "Incidente eliminada correctamente",
                                "Incidente."
                            );
                        })
                        .catch((e) => {
                            console.warn(e);
                            toastr.error(
                                "Error al cargar incidente",
                                "Incidente"
                            );
                        });
                }
            );

            this.justificado = false;
            this.edicion = false;
        },

        onClickDonwload: function () {
            $("#intervalo-reporte-modal").modal("show");
        },

        closeModalIncidencias: function () {
            //if (this.edicion) alert("Cerrrando modo edicion");
            $("#incidentes-modal").modal("hide");
            this.limpiarFormularioIncidentes();
            this.edicion = false;
        },
        closeModalEvidencias: function () {
            this.temp_evidencias = [];
            $("#evidencias-modal").modal("hide");
        },
        limpiarFormularioIncidentes: function () {
            const $form = document.getElementById("modal-incidentes");
            // Limpiando formulario

            this.justificado = true;

            $form.tipo_incidente.value = "-";
            $form.justificacion_estado.value = "0";
            $form.parentesco.value = "-";
            $form.text_incidente.value = "";
            $form.text_justificacion.value = "";

            this.edicion = false;
            this.justificado = false;
        },
        limpiarFormularioEvidencias: function () {
            const $form = document.getElementById("form-evidencias");
            // Limpiando formulario
            $form.descripcion.value = "";
            $form.file_evidencia.value = "";
        },
        formatearFecha: function (fechaStr, formato = "d/m/Y") {
            // bede recibir en formato "2000-04-10"
            if (fechaStr == null || fechaStr == "") return "-";
            if (Date.parse(fechaStr)) {
                let partes = fechaStr.split("-");
                var fecha = new Date(partes[0], partes[1] - 1, partes[2]);
                const pad = (n) => ((n + "").length === 2 ? n : "0" + n);

                switch (formato) {
                    case "Y":
                        return fecha.getFullYear();
                    case "d/m/Y h:m":
                        return (
                            pad(fecha.getDate()) +
                            "/" +
                            pad(fecha.getMonth() + 1) +
                            "/" +
                            fecha.getFullYear() +
                            " " +
                            fecha.getHours() +
                            ":" +
                            fecha.getMinutes() +
                            " "
                        );
                    default:
                        return (
                            pad(fecha.getDate()) +
                            "/" +
                            pad(fecha.getMonth() + 1) +
                            "/" +
                            fecha.getFullYear()
                        );
                }
            } else
                console.warn(
                    "la fecha " + fechaStr + " no tiene el formato adecuado"
                );
        },
        toggleState: function ($event) {
            this.justificado = $event.target.value == "1";
        },
    },
    created: function () {
        this.cargarData();
    },
});

/*
    swal("Error", "No se ha encontrado el DNI especificado.", "error");

    swal({
    title: "¿Ésta seguro de revertir este pago?",
    text: "Se añadirá una nota de crédito, y este pago sera revertido.",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: 'Si, Revertir',
    closeOnConfirm: true
    }, function () {
    $.ajax({
        type: "post",
        dataType: 'json',
        data: arr,
        url: "{{ action('PaymentController@store') }}",
        success: function (data) {
            console.log(data);
            if (data.success) {
                show_history_payment_modal(data.installment_id, true);
            } else {
                alert('error');
            }

        }
    });
    });
    */

/*
                toastr.success(
                    "Alumno actualizado correctamente.",
                    "Actualizado correctamente"
                );

                        
                toastr.error(
                    "Algunos campos estan incompletos.",
                    "Error al actualizar alumno"
                );
                
                toastr.warning(
                            "Datos de la matricula restablecidos correctamente.",
                            "Cancelar"
                        );

////Colores:
            verde claro     E5F1E5
            verde fuerte    25852A
            rojo claro      FAEDEE
            rojo fuerte     E24F3F
            amarillo claro  FBF5EB
            amarillo fuerte E6C94F
            gris claro      EAEDF1
            gris fuerte     AFB1B8

            data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
*/
