@extends('layouts.app')

@section('title', 'Editar ticket de descarga')

@section('content')
  <section class="section" onload="checkExistCp()">
    <div class="section-header">
      <h3 class="page__heading">Editar ticket de descarga</h3>
    </div>        
    <div class="section-body">
      <form action="{{ route('admin.ticket.update', $ticket->id) }}" method="POST" id='frmTicket'>
        @csrf
        @method('PATCH')
        <input type="hidden" name="deletedCategories" id='deletedCategories' value="[]">
        <div class="card mb-4">
          <div class="card-body">
            <div class="col-md-10 col-12 d-flex align-items-center py-1">
              <label class="m-0">Puerto</label>
              <div class="col-md-4 col-12">
                  <input 
                  class="form-control" 
                  value="{{$ticket->port}}"
                  disabled          
                  type="text" 
                />
              </div>
            </div>
          </div>
        </div>
        <div class="card mb-0">
          <div class="card-body">
            <div class="row col-12">
              <div class="row col-12 pr-0" id="carta-porte-section">
                <div class="form-group col-md-4 col-12">
                  <label for="nro_carta_porte">Número de carta de porte</label>
                  <input 
                  class="form-control"
                  value="{{$ticket->nro_carta_porte}}"
                  disabled
                  type="number" 
                />
                  <div class="d-flex align-items-center">                   
                  </div>
                </div>
                <div class="form-group col-md-4 col-12">
                  <label for="nro_ticket">Número de ticket</label>
                  <input type="number" name="nro_ticket" class="form-control" value="{{$ticket->nro_ticket}}" required>
                </div>
                <div class="form-group col-md-4 col-12">
                  <label for="nro_contract">Número de contrato</label>
                  <input type="number" name="nro_contract" class="form-control" value="{{$ticket->nro_contract}}" required>
                </div>
              </div>
              <div class="row col-12 justify-content-between align-items-center download-weight-section mb-3 disabled" id="weight-section">  
                <div class="col-md-10 col-12 d-flex align-items-center">
                  <div class="col-md-2 col-12"></div>
                  <div class="col-md-3 col-12">
                    <label>Fecha y hora</label>
                  </div>
                  <div class="col-md-2 col-12 pl-0">
                    <label>Kilos</label>
                  </div>
                </div>
                <div class="col-md-10 col-12 d-flex align-items-center py-1">
                  <div class="col-md-2 col-12">
                    <label>Peso bruto</label>
                  </div>
                  <div class="col-md-3 col-12">
                    <input type="date" name="date_gross_weight" class="form-control" required value={{$ticket->date_gross_weight}}/>
                  </div>
                  <div class="col-md-2 col-12 pl-0">
                    <input 
                      class="form-control" 
                      id='inputGrossWeight' 
                      name="gross_weight" 
                      onchange="setKgDiscount()"
                      value="{{$ticket->gross_weight}}"
                      required
                      type="number" 
                    />
                  </div>
                </div>
                <div class="col-md-10 col-12 d-flex align-items-center py-1">
                  <div class="col-md-2 col-12">
                    <label>Peso tara</label>
                  </div>
                  <div class="col-md-3 col-12">
                    <input type="date" name="date_tare_weight" class="form-control" required value={{$ticket->date_tare_weight}}>
                  </div>
                  <div class="col-md-2 col-12 pl-0">
                    <input 
                      class="form-control" 
                      id='inputTareWeight' 
                      name="tare_weight" 
                      onchange="setKgDiscount()"
                      value="{{$ticket->tare_weight}}"
                      required 
                      type="number" 
                    >
                  </div>
                </div>
                <div class="col-md-10 col-12 d-flex align-items-center py-1">
                  <div class="col-md-2 col-12">
                    <label>Peso neto</label>
                  </div>
                  <div class="col-md-3 col-12"></div>
                  <div class="col-md-2 col-12 pl-0">
                    <input type="number" name="net_weight" value="{{$ticket->net_weight}}" class="form-control" id='inputNetWeight' required readonly>
                  </div>
                </div>
                <div class="col-md-10 col-12 d-flex align-items-center py-1">
                  <div class="col-md-2 col-12">
                    <label>Total descuento</label>
                  </div>
                  <div class="col-md-3 col-12"></div>
                  <div class="col-md-2 col-12 pl-0">
                    <input type="number" name="total_discount" value="{{$ticket->total_discount}}" class="form-control" id='totalDiscount' required readonly>
                  </div>
                </div>
                <div class="col-md-10 col-12 d-flex align-items-center py-1">
                  <div class="col-md-2 col-12">  
                    <label>Neto comercial</label>
                  </div>
                  <div class="col-md-3 col-12"></div>
                  <div class="col-md-2 col-12 pl-0">
                    <input type="number" name="commercial_net" value="{{$ticket->commercial_net}}" class="form-control" id='inputComercialNet' required readonly>
                  </div> 
                </div>
                <div class="col-md-10 col-12 d-flex align-items-center py-1">
                  <div class="col-md-2 col-12">
                    <label>Condición</label>
                  </div>
                  <div class="col-md-3 col-12">
                    <select class="form-control select2" name="condition" value="{{$ticket->condition}}" id="condition" required>
                      <option value="" selected disabled>Seleccione</option>
                      @foreach($conditions as $condition)
                        <option value="{{ $condition }}" @if ($condition == $ticket->condition) selected @endif>
                          {{ $condition }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group col-12 pb-1" id="observation-section">
                <label for="observation">Observaciones</label>
                <div class="d-flex align-items-center">
                  <textarea 
                    class="form-control"
                    id="observation"
                    name="observation"
                    rows="10"
                    cols='50'
                    style="height: 8em !important;"
                  >{{ $ticket->observation }}</textarea>
                </div>
              </div>
              <input type="hidden" name="rubros" id='rubros'>
              <div class="row col-12 align-items-center" id="campos">
                <div class="row col-12 justify-content-end pr-4">
                  <a href="#" id='addInputs' title="Agregar más de uno" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                  </a>
                </div>
              </div>
              <div class="col-12 align-items-center pt-3" id="no-category-warning">
                <p class="text-warning">¡El grano de esta CP no tiene rubros relacionados!</p>
              </div>
              <div class="col-12 align-items-center pt-3">
              <button class="btn btn-primary btn-icon" id="save-btn">Guardar</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>
  <style>
    /* para esconder el up/down number de los input type="number" */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    input[type=number] {
      -moz-appearance: textfield;
    }
  </style>
  <script>
    let arrayIds = [];
    let nextInputCount = 0;

    /**
     * Posible values:
     *   LDC
     *   CARGILL
     *   ADM
     *   TERMINAL
     *   VITERRA
     */
    const PORTS = {!! json_encode($ports) !!};
    const form = document.getElementById('frmTicket');
    //array de rubros eliminados
    const arrDeleted = [];
    //referencia tabla GrainCategories
    const categories     = @json($ticket->categories);
    //referencia tabla TicketCategories
    const cantCategories = @json($ticket->ticketCategory);
    
    $( document).ready(function() {
      checkExistCp();
      validatePort();
      nextInputCount = cantCategories.length;
      $('.select2').select2();
    });

    let isCartaPorteValid  = false;
    let totalDiscounts     = 0;
    let isViterraPort      = false;
    let isEditableCategory = false;

    let cartaPorteSection = document.getElementById('carta-porte-section');
    let nroCartaPort      = document.getElementById('nroCartaPorte');

    let weightSection    = document.getElementById('weight-section');
    let grossWeigthInput = document.getElementById('inputGrossWeight');
    let tareWeightInput  = document.getElementById('inputTareWeight');
    let netWeightInput   = document.getElementById('inputNetWeight');

    let observationSection = document.getElementById('observation-section');

    let actualGrainCategories = [];
    let inputGroup            = document.getElementById('campos');
    let addInputsButton       = document.getElementById('addInputs');

    let noCategoryWarning = document.getElementById('no-category-warning');

    let saveButton = document.getElementById('save-btn');

    /**
     * By default, `campos` will be hidden until `carta porte` has a valid value and until its grain has associated categories
     */
    inputGroup.style.display        = 'none';
    noCategoryWarning.style.display = 'none';

    /**
     * Remove "disabled" class from each html input
     * @param {HTMLInputElement[]} inputs
     */
    const enableElements = (inputs) => {
      if (inputs.length) {
        for (const input of inputs) {
          input.classList.remove('disabled');
        }
      }
    }

    /**
     * Set the `selected` property to the option selected in the dropwdown.
     * Check also if the selected port is Viterra.
     */
    const validatePort = () => {
      const actualPort = "{{$ticket->port}}";
 
      $("#port option").each(function(){
        if ($(this).text() === actualPort)
          $(this).prop("selected", "selected");
      });
      if (actualPort) {
        enableElements([cartaPorteSection])
        if (actualPort === PORTS.VITERRA){
          isViterraPort = true;
        }
        else           // just get discount and kg discount with `humedad`
          isViterraPort = false;
      };
    }

    /**
     * Check if there is a `carta de porte` in the database
     */
    const checkExistCp = () => {
      const nroCartaPorte = {{$ticket->nro_carta_porte}};
      if (nroCartaPorte > 0) {
        $.ajax({
          url: 'carta-porte/'+nroCartaPorte,
          cache: true,
          type: 'GET',
          dataType: 'json',
          success: function (exists) {
            isCartaPorteValid = true;
            getGrainCategories(exists.grain_id)
          },
          error: function (jqXHR, textStatus, errorThrown) {

            icon.title='Carta de porte no válida';
            isCartaPorteValid = false;
            disableElements([saveButton]);
            inputGroup.style.display = 'none';
          }
        });
      }
    }

    /**
     * Get all the categories for one specific grain
     * @param {number} grainId
     */
    const getGrainCategories = (grainId) => {
      if (+grainId > 0) {
        $.ajax({
          url: 'grain/'+grainId+'/categories',
          cache: true,
          type: 'GET',
          dataType: 'json',
          success: function (exists) {         
            if (exists?.categories?.length > 0) {
              setGrainCategories(exists.categories)
              inputGroup.style.display = 'flex';
            } else {
              noCategoryWarning.style.display = 'flex';
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            //
          }
        });
      }
    }

    /**
     * Set the grain categories to the select input
     * @param {[]} categories
     * @param {number} count
     */
    const setGrainCategories = (categories, count = 1) => {
      let grainCategoryInput = document.getElementById(`grain_category_id${count}`);
      categories.forEach((category) => {
        let opt       = document.createElement('option');
        opt.value     = category?.id;
        opt.innerHTML = category?.name;
        grainCategoryInput.appendChild(opt);
      });
      actualGrainCategories = categories;
    }


    grossWeigthInput.addEventListener("keyup", () => {
      if (grossWeigthInput.value.length 
        && tareWeightInput.value.length
      ){
        enableElements([observationSection, inputGroup])
        calculateValue(nextInputCount);
      } else {
        disableElements([observationSection, inputGroup])
      }
    });
    tareWeightInput.addEventListener("keyup", () => {
      if (grossWeigthInput.value.length 
        && tareWeightInput.value.length
      ){
        enableElements([observationSection,inputGroup])
        calculateValue(nextInputCount);
      } else {
        disableElements([observationSection, inputGroup])
      }
    });

    /**
     * Calculate the discount value that corresponds to the value per category passed, based on the div id obtained with the count parameter
     * @param {number} count
     */
     const calculateValue = (count) => {
      if (isCartaPorteValid) {
        let nroCartaPorte   = {{$ticket->nro_carta_porte}};
        let grainCategoryId = document.getElementById(`grain_category_id${count}`).value;
        let valueInput      = document.getElementById(`valueInput${count}`).value || null;
        
        // `humedad` = id 10 , `chamico` = id 2. This is defined by default in `GrainCategorySeeder`
        isEditableCategory = Boolean(+grainCategoryId === 10 || +grainCategoryId === 2);

        document.getElementById(`discountInput${count}`).readOnly = isViterraPort || isEditableCategory ? true : false;
        document.getElementById(`discountKg${count}`).readOnly = isViterraPort || isEditableCategory ? true : false;

        let actualUrl = `carta-porte/${nroCartaPorte}/${valueInput}?grain_cat=${grainCategoryId}`;

        if (isViterraPort || isEditableCategory && valueInput) {
          $.ajax({
            url: actualUrl,
            cache: true,
            type: 'GET',
            dataType: 'json',
            success: function (resp) {
              if (resp) {
                document.getElementById(`discountInput${count}`).value = formatDecimalNumber(resp.discount);
                setKgDiscount();
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {
              // alert(jqXHR.responseJSON.error);
            }
          });
        }
      }
    };

    addInputsButton.addEventListener('click', (e) => {
                                      e.preventDefault();
                                      addInputs();
                                    });

    /**
     * Add a new input group (`category`, `value`, `discount`, `kg. discount`) by pressing the "+" button.
     */
    const addInputs = (data) => {
      nextInputCount++;
      let name = '';

      categories.forEach(cat => {
        if(cat.id == data?.grain_category_id){
          name = cat.name;
        }
      });

      arrayIds.push(nextInputCount);

      let div = `
        <div id="div${nextInputCount}" class="row col-md-11 col-12 align-items-center">
          <div class="form-group col-md-3 col-12">
            <label for="grain_category">Rubro</label>
            <select 
              class="form-control select2" 
              id="grain_category_id${nextInputCount}" 
              name="grain_category_id" 
              onchange="calculateValue(${nextInputCount})"
              required
            >
              <option value="${data?.grain_category_id}" selected="${name ? true : false}">${name || 'SELECCIONE'}</option>
            </select>
          </div>
          <div class="form-group col-md-3 col-12">
            <label for="value">Valor</label>                            
            <input type="number" name="value" onchange="calculateValue(${nextInputCount})" class="form-control" id='valueInput${nextInputCount}' 
            value="${data?.value ?? ''}" required>
          </div>
          <div class="form-group col-md-3 col-12">
            <label for="discount">Descuento</label>
            <input 
              class="form-control" 
              id='discountInput${nextInputCount}' 
              min="0"
              name="discount" 
              onchange="calculateTotalDiscount()"
              readonly 
              required 
              step="0.01"
              type="number"
              value="${data?.discount ?? ''}"
            />
          </div>
          <div class="form-group col-md-3 col-12">                            
            <label for="discount_kg">Kilos descuento</label>                            
            <input 
              class="form-control" 
              id='discountKg${nextInputCount}' 
              min="0"
              name="discount_kg" 
              readonly
              required 
              step="0.01"
              type="number" 
              value="${data?.discount_kg ?? ''}"
            />
          </div>
        </div>
        <div class="col-1 text-center">
          <button type="submit" id='deleteInput${nextInputCount}' onclick="deleteInput(${nextInputCount})" title="Eliminar campos" class="btn btn-danger">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      `;
      
      document.getElementById("campos").insertAdjacentHTML('beforeend', div);
      setGrainCategories(actualGrainCategories, nextInputCount);
      $('.select2').select2();
    }

    /**
     * Set the value of kg discount based on ???
     */
    const setKgDiscount = () => {
      if (grossWeigthInput?.value && tareWeightInput?.value) {
        let netWeight        = grossWeigthInput?.value - tareWeightInput?.value;
        netWeightInput.value = netWeight;
        if (netWeight) {
          calculateTotalDiscount();
        }
      }
    }

    form.addEventListener('submit', e => {
      let rubrosArr = [];
      for (const id of arrayIds) {
        const select = document.getElementById(`grain_category_id${id}`);
        rubrosArr.push({
          'grain_category_id' : select.options[select.selectedIndex].value,
          'value'             : document.getElementById(`valueInput${id}`).value,
          'discount'          : document.getElementById(`discountInput${id}`).value || 0,
          'discount_kg'       : document.getElementById(`discountKg${id}`).value || 0,
        });
        document.getElementById('rubros').value = JSON.stringify(rubrosArr);
      }
    })

    cantCategories.forEach((category) => {
      addInputs(category);
    })
    
    /**
     * Remove an input group (`category`, `value`, `discount`, `kg. discount`) by pressing the "🗑 (delete)" button.
     * @param {number} id 
     */
    const deleteInput = (id) => {
      const inputDeletedCategories = document.getElementById("deletedCategories");
      const inputGroup             = document.getElementById(`div${id}`);
      const categoryInput          = document.getElementById(`grain_category_id${id}`).value;
      const inputValue             = document.getElementById(`valueInput${id}`).value;
      const inputDiscount          = document.getElementById(`discountInput${id}`).value;

      const deleteBtn = document.getElementById(`deleteInput${id}`).parentNode;
      deleteBtn.remove();
      inputGroup.remove();
      
      unset(id);

      arrDeleted.push({ 'discount' : inputDiscount, 'value': inputValue, category_id: categoryInput });

      inputDeletedCategories.value =  JSON.stringify(arrDeleted);
    }
    
    /**
     * Calculate the total download ticket discount
     */
     const calculateTotalDiscount = () => {
      let totalDiscount = 0;

      arrayIds.forEach(id => {
        let discountInput = +document.getElementById(`discountInput${id}`).value;
        let kgDiscount    = netWeightInput?.value * discountInput / 100;

        // Se almacena el kg discount de cada input y luego lo almacenamos en variable para realizar el calculo nuevamente
        document.getElementById(`discountKg${id}`).value = formatDecimalNumber(kgDiscount);
        
        let discountKg = +document.getElementById(`discountKg${id}`)?.value;
        
        totalDiscount = (totalDiscount + discountKg) - ((totalDiscount * discountKg) / 100);

        document.getElementById('inputComercialNet').value = formatDecimalNumber((netWeightInput.value - totalDiscount));
      });

      document.getElementById(`totalDiscount`).value = formatDecimalNumber(totalDiscount);
    }

    /**
     * Delete the id from the `arrayIds` array
     * @param {number} idToUnset
     */
     const unset = (idToUnset) => {
      arrayIds = arrayIds.filter((id) => id !== idToUnset);
      calculateTotalDiscount();
    }

    const checkDeletedArray = ( id) => {
      arrDeleted = arrDeleted.filter((i) => i.id !== id);
    }

    /**
     * Format the number with decimals to show only two decimals without rounding
     * @param {number} number
     */
    const formatDecimalNumber = (number) => Math.floor(number * 100) / 100;
  </script>
@endsection
