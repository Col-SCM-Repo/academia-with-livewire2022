<!------------------------------- Begin: matricula ------------------------------->
<div wire:ignore.self>
    Una Matricula


    Dtos requridos

    // Table matricula
    * code 
    * type
    * student_id
    * relative_id	
    * classroom_id	
    * relative_relationship	
    * user_id	
    * career_id	
    * payment_type	
    * fees_quantity	
    * period_cost	
    * cancelled	
    * observations	

<br>
    // Cuotas (payments)    al matricularse se indica las cuotas
    * enrollment_id	
    * order	
    * type	
    * amount	
    * state

<br>
    // Pagos (pagos realizados pòr el alumno)
    * installment_id	
    * amount	
    * type	
    * concept_type	
    * user_id	
    * payment_id	
    * serie	
    * numeration

    <br>
    para pagos se tiene en cuenta la tabla secuences
    * se utiliza un numero de serie (revisar) *





</div>
<!------------------------------- End: matricula ------------------------------->
