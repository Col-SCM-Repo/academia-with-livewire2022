import axios from "axios";
import Vue from "vue";

const formTypes = {
    MATRICULA: "matricula",
    ALUMNO: "alumno",
    APODERADO: "apoderado",
};

const pagoTypes = {
    CASH: "cash",
    CREDIT: "credit",
};

var indexVue = new Vue({
    el: "#app",
    data: {
        baseUrl: $("#baseUrl").val() + "/matricula",
        enrollmentId: $("#id").val(),
        initialState: null,
        editMatricula: false,
        editAlumno: false,
        editApoderado: false,
        enrollment: null,
        $formMatricula: null,
        $formAlumno: null,
        $formApoderado: null,
    },
    methods: {
        actualizarMatricula(event) {
            //debugger;
            event.preventDefault();
            //this.showDataForm(this.$formMatricula, event.target, "Matricula");

            if (
                event.target.classroom_id.value != "0" &&
                event.target.career_id.value != "0"
            ) {
                const url = `${this.baseUrl}/updateMatricula/${this.enrollmentId}`;
                // alert(url);
                // Enviar peticion

                const data = new FormData(event.target);
                data.append("classroom_id", event.target.classroom_id.value);
                data.append("career_id", event.target.career_id.value);
                //console.log("classroom_id", event.target.classroom_id.value);
                //console.log("career_id", event.target.career_id.value);

                const refresh_bind = this.refreshData.bind(this);

                swal(
                    {
                        title: "¿Ésta seguro modificar el pago?",
                        text: "Se ha detectado cambios en el pago y cuotas, si acepta  se recalcularan los montos",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#F8C786",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Si, Modificar",
                        cancelButtonText: "Cancelar",
                        closeOnConfirm: true,
                    },
                    function () {
                        //const data = new FormData(event.target);
                        data.append("recalcular", true);
                        axios({
                            method: "POST",
                            responseType: "json",
                            data,
                            url,
                        })
                            .then((response) => {
                                console.log("Matricula", response.data);
                                toastr.success(
                                    "Matricula actualizada correctamente.",
                                    "Actualizado correctamente"
                                );
                                //actualizar
                                refresh_bind();
                            })
                            .catch((err) => {
                                console.warn(err);
                                toastr.warning(
                                    "Error en la actualizacion.",
                                    "Matricula"
                                );
                            });
                    }
                );
                this.toggelModeEdition(formTypes.MATRICULA, false);
            } else {
                toastr.error(
                    "Algunos campos estan incompletos.",
                    "Error al actualizar matricula"
                );
            }
        },

        actualizarAlumno(event) {
            event.preventDefault();

            //this.showDataForm(this.$formAlumno, event.target, "Alumno");

            let error =
                event.target.student_document_number.value.length >= 8 &&
                event.target.student_district_id.value != 0 &&
                event.target.student_district_id.value != "" &&
                event.target.student_ie_id.value != 0 &&
                event.target.student_ie_id.value != "";

            if (error) {
                // Enviar peticion
                const data = new FormData(event.target);
                data.append(
                    "student_document_number",
                    event.target.student_document_number.value
                );

                data.append(
                    "student_district_id",
                    event.target.student_district_id.value
                );
                data.append("student_ie_id", event.target.student_ie_id.value);

                const url = `${this.baseUrl}/updateAlumno/${this.enrollmentId}`;
                axios({ method: "POST", responseType: "json", data, url })
                    .then((response) => {
                        console.log("Alumno", response.data);
                        toastr.success(
                            "Alumno actualizado correctamente.",
                            "Actualizado correctamente"
                        );
                        event.target.student_document_number.disabled = true;
                    })
                    .catch((err) => {
                        console.warn(err);
                        toastr.warning("Error en la actualizacion.", "Alumno");
                    });
                this.toggelModeEdition(formTypes.ALUMNO, false);
            } else {
                toastr.error(
                    "Algunos campos estan incompletos.",
                    "Error al actualizar alumno"
                );
            }
        },

        actualizarApoderado(event) {
            //debugger;
            event.preventDefault();

            if (event.target.relative_document_number.value.length >= 8) {
                const data = new FormData(event.target);
                data.append(
                    "relative_document_number",
                    event.target.relative_document_number.value
                );
                const url = `${this.baseUrl}/updateApoderado/${this.enrollmentId}`;
                axios({ method: "POST", responseType: "json", data, url })
                    .then((response) => {
                        console.log("Apoderado", response.data);
                        toastr.success(
                            "Apoderado actualizado correctamente.",
                            "Actualizado correctamente"
                        );
                        this.refreshData();
                        event.target.relative_document_number.disabled = true;
                    })
                    .catch((err) => {
                        console.warn(err);
                        toastr.warning(
                            "Error en la actualizacion.",
                            "Apoderado"
                        );
                    });
                this.toggelModeEdition(formTypes.APODERADO, false);
            }
        },

        toggelModeEdition(elemento, edicion = true, cancel = false) {
            switch (elemento) {
                case formTypes.MATRICULA:
                    this.editMatricula = edicion;
                    console.log(edicion);
                    if (edicion) {
                        setTimeout(() => {
                            console.log(
                                document.getElementById("form_matricula")
                                    .payment_type?.value
                            );
                            let value = document.getElementById(
                                "payment_type_cash"
                            ).checked
                                ? pagoTypes.CASH
                                : pagoTypes.CREDIT;

                            if (value == pagoTypes.CASH) {
                                $("#fees_quantity_div").css("display", "none");
                                $("#fee_cost_div").css("display", "none");
                            }

                            if (value == pagoTypes.CREDIT) {
                                $("#fees_quantity_div").css("display", "block");
                                $("#fee_cost_div").css("display", "block");
                            }
                        }, 200);
                        this.cargarPropsMatricula();
                    }

                    if (cancel) {
                        toastr.warning(
                            "Datos de la matricula restablecidos correctamente.",
                            "Cancelar"
                        );
                        this.resetForm(formTypes.MATRICULA, "form_matricula");
                    }
                    break;
                case formTypes.ALUMNO:
                    this.editAlumno = edicion;
                    if (cancel) {
                        toastr.warning(
                            "Datos del alumno restablecidos correctamente.",
                            "Cancelar"
                        );
                        this.resetForm(formTypes.ALUMNO, "form_alumno");
                    }
                    break;
                case formTypes.APODERADO:
                    this.editApoderado = edicion;
                    if (cancel) {
                        toastr.warning(
                            "Datos del apoderado restablecidos correctamente.",
                            "Cancelar"
                        );
                        this.resetForm(formTypes.APODERADO, "form_apoderado");
                    }
                    break;
            }
        },
        radioTipoPago: function (event) {
            //console.log(event.target.value);

            let value = event.target.value;

            if (value == pagoTypes.CASH) {
                $("#fees_quantity_div").css("display", "none");
                $("#fee_cost_div").css("display", "none");
                $("#enrollment_cost").val("0.00");
            }

            if (value == pagoTypes.CREDIT) {
                $("#fees_quantity_div").css("display", "block");
                $("#fee_cost_div").css("display", "block");

                $("#fees_quantity").unbind();
                $("#fees_quantity").keyup(function (e) {
                    fee_cost();
                });

                $("#enrollment_cost").val("50.00");
            }
        },

        fee_cost: function () {
            var period_cost = $("#period_cost").val();
            var fees_quantity = $("#fees_quantity").val();
            var fee_cost = 0.0;

            if (period_cost != "" && fees_quantity != 0) {
                fee_cost = Number(
                    Number(period_cost) / Number(fees_quantity)
                ).toFixed(2);
            }

            $("#fee_cost").val(fee_cost);
        },
        resetForm: function (formulario_name, form_id) {
            let form = null;
            switch (formulario_name) {
                case formTypes.MATRICULA:
                    form = null;
                    this.cargarPropsMatricula();
                    break;
                case formTypes.ALUMNO:
                    form = null;
                    this.cargarPropsAlumno();
                    break;
                case formTypes.APODERADO:
                    form = null;
                    this.cargarPropsApoderado();
                    break;
            }
        },
        /********************************************** DEV **********************************************/
        showDataForm: function ($form, $form_event, nombreForm = "formulario") {
            console.error(nombreForm);
            console.warn("form=> ", $form);
            console.warn("form_event=> ", $form_event);
            for (let i = 0; i < $form.elements.length; i++) {
                console.log(
                    $form.elements[i].name + "  ->  " + $form.elements[i].value
                );
            }
            console.error(
                "---------------------- event ------------------------"
            );
            for (let i = 0; i < $form_event.elements.length; i++) {
                console.log(
                    $form_event.elements[i].name +
                        "  ->  " +
                        $form_event.elements[i].value
                );
            }
        },
        refreshData: function () {
            let url = this.baseUrl + "/informacion-matricula";
            const data = { id: this.enrollmentId };

            const cargarPropsMatricula_bind =
                this.cargarPropsMatricula.bind(this);
            const cargarPropsAlumno_bind = this.cargarPropsAlumno.bind(this);
            const cargarPropsApoderado_bind =
                this.cargarPropsApoderado.bind(this);

            axios({ url, data, method: "POST", responseType: "json" })
                .then((response) => {
                    this.enrollment = response.data;
                    console.log("Data actualizada", this.enrollment);
                    setTimeout(() => {
                        cargarPropsMatricula_bind();
                        cargarPropsAlumno_bind();
                        cargarPropsApoderado_bind();
                    }, 200);
                })
                .catch((err) => {
                    console.warn(err);
                });
            url = this.baseUrl + "/installment-info/" + this.enrollmentId;
            //alert("actualizando pagos");
            axios(url)
                .then((response) => {
                    //console.log(response.data);
                    document.getElementById(
                        "installments_control_container"
                    ).innerHTML = response.data;
                })
                .catch(() => {
                    toastr.error("Error en la actualizacion.", "Apoderado");
                });
        },
        getProps: function (propiedad) {
            switch (propiedad) {
                case "enrollment_amount":
                    const installment = this.enrollment?.installments?.filter(
                        (instal) => instal.type == "enrollment"
                    );
                    console.log(installment);
                    if (installment.length > 0) return installment[0].amount;
                    else return "error";

                default:
                    return "-";
            }
        },
        cargarPropsMatricula: function () {
            //debugger;
            if (this.enrollment) {
                let $form = document.getElementById("form_matricula");

                $form.type.value = this.enrollment.type;
                $form.classroom.value =
                    this.enrollment.classroom.level.period.name +
                    " - " +
                    this.enrollment.classroom.level.level_type.description +
                    " - " +
                    this.enrollment.classroom.name;
                // $enrollment->classroom->level->period->name.' - '.$enrollment->classroom->level->level_type->description.' - '.$enrollment->classroom->name
                $form.classroom_id.value = this.enrollment.classroom.id;
                $form.career.value = this.enrollment.career.career;
                $form.career_id.value = this.enrollment.career_id;

                //Contenedores - vista edicion
                if (this.editMatricula) {
                    setTimeout(() => {
                        $form.payment_type.value = this.enrollment.payment_type;
                        $form.hidden_payment_type_value.value =
                            this.enrollment.payment_type;
                        $form.enrollment_cost.value =
                            this.getProps("enrollment_amount");

                        let period_cost_temp = parseFloat(
                            this.enrollment.period_cost
                        );
                        let fees_quantity_temp = parseInt(
                            this.enrollment.fees_quantity
                        );

                        $form.period_cost.value = period_cost_temp;
                        $form.fees_quantity.value = fees_quantity_temp;
                        $form.observations.value = this.enrollment.observations;

                        document.getElementById("fee_cost").value =
                            period_cost_temp / fees_quantity_temp;
                    }, 200);
                }

                $form.classroom.disabled = true;
                $form.career.disabled = true;
                // photo
            } else console.warn("No se encuentra data del enrollent");
        },
        cargarPropsAlumno: function () {
            if (this.enrollment) {
                let $form = document.getElementById("form_alumno");

                $form.student_document_number.value =
                    this.enrollment.student.entity.document_number;
                $form.hidden_student_id.value = this.enrollment.student.id;
                $form.student_father_lastname.value =
                    this.enrollment.student.entity.father_lastname;
                $form.student_mother_lastname.value =
                    this.enrollment.student.entity.mother_lastname;
                $form.student_name.value = this.enrollment.student.entity.name;
                $form.student_birth_date.value = this.formatearFecha(
                    this.enrollment.student.entity.birth_date
                );
                $form.student_telephone.value =
                    this.enrollment.student.entity.telephone;
                $form.student_address.value =
                    this.enrollment.student.entity.address;
                $form.student_district.value =
                    this.enrollment.student.entity.district.name;
                $form.student_district_id.value =
                    this.enrollment.student.entity.district.id;
                $form.student_ie.value = this.enrollment.student.school.name;
                $form.hidden_student_ie_id.value =
                    this.enrollment.student.school.id;
                $form.student_graduation_year.value =
                    this.enrollment.student.graduation_year;

                $form.student_document_number.disabled = true;
                $form.student_district.disabled = true;
                $form.student_ie.disabled = true;
                // photo
            } else console.warn("No se encuentra data del enrollent");
        },
        cargarPropsApoderado: function () {
            if (this.enrollment) {
                let $form = document.getElementById("form_apoderado");
                $form.relative_document_number.value =
                    this.enrollment.relative.entity.document_number;
                $form.relative_id.value = this.enrollment.relative.id;
                $form.relative_father_lastname.value =
                    this.enrollment.relative.entity.father_lastname;
                $form.relative_mother_lastname.value =
                    this.enrollment.relative.entity.mother_lastname;
                $form.relative_name.value =
                    this.enrollment.relative.entity.name;
                $form.relative_birth_date.value = this.formatearFecha(
                    this.enrollment.relative.entity.birth_date
                ); //d/m/Y
                $form.relative_telephone.value =
                    this.enrollment.relative.entity.telephone;
                $form.relative_occupation.value =
                    this.enrollment.relative.occupation;
                $form.relative_address.value =
                    this.enrollment.relative.entity.address;
                $form.relative_relationship.value =
                    this.enrollment.relative_relationship;

                $form.relative_document_number.disabled = true;
            } else console.warn("No se encuentra data del enrollent");
        },
        formatearFecha: function (fechaStr, formato = "d/m/Y") {
            // bede recibir en formato "2000-04-10"
            if (Date.parse(fechaStr)) {
                let partes = fechaStr.split("-");
                var fecha = new Date(partes[0], partes[1] - 1, partes[2]);
                const pad = (n) => ((n + "").length === 2 ? n : "0" + n);

                switch (formato) {
                    case "Y":
                        return fecha.getFullYear();
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
    },
    created: function () {
        this.refreshData();
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
