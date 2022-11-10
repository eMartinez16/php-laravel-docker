@extends('layouts.app')

@section('title', 'Ticket de descarga')

@section('content')
  <section class="section">
    <div class="section-header">
      <h3 class="page__heading">Nuevo ticket de descarga</h3>
    </div>        
    <div class="section-body">
      <form action="{{ route('admin.ticket.store') }}" method="POST" id='frmTicket'>
        @csrf
        <div class="card mb-4">
          <div class="card-body">
            <div class="col-md-10 col-12 d-flex align-items-center py-1">
              <label class="m-0">Puertos</label>
              <div class="col-md-4 col-12">
                <select 
                  class="form-control select2"
                  id="port"
                  name="port"
                  onchange="validatePort()"
                  required
                >
                  <option value="" selected disabled>Seleccione</option>
                  @foreach($ports as $port)
                    <option value="{{ $port }}">
                      {{ $port }}
                    </option>
                  @endforeach
                </select>
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
                  <div class="d-flex align-items-center">
                    <input
                      class="form-control"
                      id="nroCartaPorte"
                      min="0"
                      name="nro_carta_porte"
                      onchange="checkExistCp()"
                      required
                      type="number"
                    /> 
                    <i class="fas ml-2" id='verifyExist' style="display:none"></i>
                  </div>
                </div>
                <div class="form-group col-md-4 col-12">
                  <label for="nro_ticket">Número de ticket</label>
                  <input type="number" name="nro_ticket" class="form-control" required>
                </div>
                <div class="form-group col-md-4 col-12">
                  <label for="nro_contract">Número de contrato</label>
                  <input type="number" name="nro_contract" class="form-control" required>
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
                    <input type="datetime-local" name="date_gross_weight" class="form-control" required />
                  </div>
                  <div class="col-md-2 col-12 pl-0">
                    <input 
                      class="form-control" 
                      id='inputGrossWeight' 
                      name="gross_weight" 
                      onchange="setKgDiscount()"
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
                    <input type="datetime-local" name="date_tare_weight" class="form-control" required>
                  </div>
                  <div class="col-md-2 col-12 pl-0">
                    <input 
                      class="form-control" 
                      id='inputTareWeight' 
                      name="tare_weight" 
                      onchange="setKgDiscount()"
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
                    <input type="number" name="net_weight" class="form-control" id='inputNetWeight' required readonly>
                  </div>
                </div>
                <div class="col-md-10 col-12 d-flex align-items-center py-1">
                  <div class="col-md-2 col-12">
                    <label>Total descuento</label>
                  </div>
                  <div class="col-md-3 col-12"></div>
                  <div class="col-md-2 col-12 pl-0">
                    <input type="number" name="total_discount" class="form-control" id='totalDiscount' required readonly>
                  </div>
                </div>
                <div class="col-md-10 col-12 d-flex align-items-center py-1">
                  <div class="col-md-2 col-12">  
                    <label>Neto comercial</label>
                  </div>
                  <div class="col-md-3 col-12"></div>
                  <div class="col-md-2 col-12 pl-0">
                    <input type="number" name="commercial_net" class="form-control" id='inputComercialNet' required readonly>
                  </div> 
                </div>
                <div class="col-md-10 col-12 d-flex align-items-center py-1">
                  <div class="col-md-2 col-12">
                    <label>Condición</label>
                  </div>
                  <div class="col-md-3 col-12">
                    <select class="form-control select2" name="condition" id="condition" required>
                      <option value="" selected disabled>Seleccione</option>
                      @foreach($conditions as $condition)
                        <option value="{{ $condition }}">
                          {{ $condition }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group col-12 pb-1 disabled" id="observation-section">
                <label for="observation">Observaciones</label>
                <div class="d-flex align-items-center">
                  <textarea 
                    class="form-control"
                    id="observation"
                    name="observation"
                    rows="10"
                    cols='50'
                    style="height: 8em !important;"
                  ></textarea>
                </div>
              </div>
              <input type="hidden" name="rubros" id='rubros'>
              <div class="row col-12 align-items-center disabled" id="campos">
                <div class="row col-md-11 col-12 align-items-center" id='div1'>
                  <div class="form-group col-md-3 col-12">
                      <label class="col-form-label" for="grain_category">Rubro</label>
                      <select 
                        class="form-control select2"
                        id="grain_category_id1"
                        name="grain_category_id"
                        onchange="calculateValue(1)"
                        required
                      >
                        <option value="" disabled>Seleccione</option>
                      </select>
                  </div>
                  <div class="form-group col-md-3 col-12">
                    <label class="col-form-label" for="value">Valor</label>
                    <input type="number" name="value" onchange="calculateValue(1)" class="form-control" id='valueInput1' required>
                  </div>
                  <div class="form-group col-md-3 col-12">
                    <label for="discount">Descuento</label>
                    <input 
                      type="number" 
                      name="discount" 
                      class="form-control" 
                      id='discountInput1'
                      required
                      readonly  
                      onchange="calculateTotalDiscount()"
                    />
                  </div>
                  <div class="form-group col-md-3 col-12">
                    <label for="discount_kg">Kilos descuento</label>
                    <input 
                      type="number" 
                      name="discount_kg" 
                      class="form-control" 
                      id='discountKg1' 
                      required 
                      readonly
                      step="0.01"
                    />
                  </div>
                </div>
                <div class="col-md-1 col-12 text-center">
                  <a href="#" id='addInputs' title="Agregar más de uno" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                  </a>
                </div>
              </div>
              <div class="col-12 align-items-center justify-content-center" id="no-category-warning">
                <h5 class="text-warning mb-0">
                  ¡El grano de esta CP no tiene 
                  <a 
                    class="text-warning bold font-weight-bold" 
                    style="text-decoration: underline !important" 
                    href="{{ route('admin.categories.index') }}"
                  >rubros relacionados</a>!
                </h5>
              </div>
              <div class="col-12 align-items-center pt-2">
                <button class="btn btn-primary btn-icon" id="save-btn">Guardar</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>
  <style>
    .disabled {
      pointer-events: none;
      opacity: 0.6;
    }
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
    'use strict';

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

    let arrayIds = [1];

    let nextInputCount     = 1;
    let isCartaPorteValid  = false;
    let totalDiscounts     = 0;
    let isViterraPort      = false;
    let isEditableCategory = false;

    let port = document.getElementById('port');

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
     * Add the class "disabled" to each html input
     * @param {HTMLInputElement[]} inputs
     */
    const disableElements = (inputs) => {
      if (inputs.length) {
        for (const input of inputs) {
          input.classList.add('disabled');
        }
      }
    }
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

    // Disable all elements until a port is selected
    disableElements([
      cartaPorteSection, 
      weightSection, 
      observationSection, 
      inputGroup, 
      saveButton
    ]);

    /**
     * Set the `selected` property to the option selected in the dropwdown.
     * Check also if the selected port is Viterra.
     */
    const validatePort = () => {
      const actualPort = port.value;
      $("#port option").each(function() {
        if ($(this).text() === actualPort)
          $(this).prop("selected", "selected");
      });
      if (actualPort) {
        enableElements([cartaPorteSection])
        if (actualPort === PORTS.VITERRA)
          isViterraPort = true;
        else 
          // just get discount and kg discount with `humedad`
          isViterraPort = false;
      };
    }

    /**
     * Check if there is a `carta de porte` in the database
     */
    const checkExistCp = () => {            
      let nroCartaPorte = nroCartaPort.value || 0;
      let icon = document.getElementById('verifyExist');
      if (nroCartaPorte > 0) {
        $.ajax({
          url: 'ticket/carta-porte/'+nroCartaPorte,
          cache: true,
          type: 'GET',
          dataType: 'json',
          success: function (exists) {
            icon.style.display = 'block';
            icon.style.color = 'green';
            icon.classList.remove('fa-times');
            icon.classList.add('fa-check');
            icon.title='Carta de porte válida';
            isCartaPorteValid = true;
            getGrainCategories(exists.grain_id);
          },
          error: function (jqXHR, textStatus, errorThrown) {
            icon.style.display = 'block';
            icon.style.color = 'red';
            icon.classList.remove('fa-check');
            icon.classList.add('fa-times');
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
          url: 'ticket/grain/'+grainId+'/categories',
          cache: true,
          type: 'GET',
          dataType: 'json',
          success: function (exists) {
            if (exists?.categories?.length > 0) {
              setGrainCategories(exists.categories)
              noCategoryWarning.style.display = 'none';
              inputGroup.style.display = 'flex';
              enableElements([saveButton]);
            } else {
              inputGroup.style.display = 'none';
              noCategoryWarning.style.display = 'flex';
              disableElements([saveButton]);
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

    //
    nroCartaPort.addEventListener("keyup", () => {
      if (nroCartaPort.value.length) { 
        enableElements([weightSection]);
      } else {
        disableElements([weightSection, observationSection, inputGroup]);
      }
    });
    grossWeigthInput.addEventListener("keyup", () => {
      if (
        nroCartaPort.value.length 
        && grossWeigthInput.value.length 
        && tareWeightInput.value.length
      ){
        enableElements([observationSection, inputGroup])
        calculateValue(nextInputCount);
      } else {
        disableElements([observationSection, inputGroup])
      }
    });
    tareWeightInput.addEventListener("keyup", () => {
      if (
        nroCartaPort.value.length 
        && grossWeigthInput.value.length 
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
        let nroCartaPorte   = nroCartaPort.value || null;
        let grainCategoryId = document.getElementById(`grain_category_id${count}`).value;
        let valueInput      = document.getElementById(`valueInput${count}`).value || null;
        
        // `humedad` = id 10 , `chamico` = id 2. This is defined by default in `GrainCategorySeeder`
        isEditableCategory = Boolean(+grainCategoryId === 10 || +grainCategoryId === 2);

        document.getElementById(`discountInput${count}`).readOnly = isViterraPort || isEditableCategory ? true : false;
        document.getElementById(`discountKg${count}`).readOnly = isViterraPort || isEditableCategory ? true : false;

        let actualUrl = `ticket/carta-porte/${nroCartaPorte}/${valueInput}?grain_cat=${grainCategoryId}`;

        if ((isViterraPort || isEditableCategory) && Boolean(valueInput)) {
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
    const addInputs = () => {
      nextInputCount++;
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
              <option value disabled>Seleccione</option>
            </select>
          </div>
          <div class="form-group col-md-3 col-12">
            <label for="value">Valor</label>                            
            <input type="number" name="value" onchange="calculateValue(${nextInputCount})" class="form-control" id='valueInput${nextInputCount}' required>
          </div>
          <div class="form-group col-md-3 col-12">
            <label for="discount">Descuento</label>
            <input 
              class="form-control" 
              id='discountInput${nextInputCount}' 
              name="discount" 
              onchange="calculateTotalDiscount()"
              readonly 
              required 
              type="number" 
            />
          </div>
          <div class="form-group col-md-3 col-12">                            
            <label for="discount_kg">Kilos descuento</label>                            
            <input type="number" name="discount_kg" class="form-control" id='discountKg${nextInputCount}' required readonly step="0.01">
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
      
      $(".select2").select2()
    }

    ;
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
      if (!isCartaPorteValid) {
        e.preventDefault();
        nroCartaPort.focus();
      } else {
        const rubros = [];
        for (let i = 1; i <= nextInputCount; i++) {
          const select = document.getElementById(`grain_category_id${i}`);
          rubros.push({
            'grain_category_id': select.options[select.selectedIndex].value,
            'value':             document.getElementById(`valueInput${i}`).value, 
            'discount':          document.getElementById(`discountInput${i}`).value || 0, 
            'discount_kg':       document.getElementById(`discountKg${i}`).value || 0, 
          });      
        }
        if (rubros.length) document.getElementById('rubros').value = JSON.stringify(rubros);
        else e.preventDefault(); // así será?
      }
    })

    /**
     * Remove an input group (`category`, `value`, `discount`, `kg. discount`) by pressing the "🗑 (delete)" button.
     * @param {number} id 
     */
    const deleteInput = (id) => {
      const inputGroup = document.getElementById(`div${id}`);
      const deleteBtn = document.getElementById(`deleteInput${id}`).parentNode;
      deleteBtn.remove();
      inputGroup.remove();
      nextInputCount = nextInputCount--;
      unset(id);
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

    
    /**
     * Format the number with decimals to show only two decimals without rounding
     * @param {number} number
     */
     const formatDecimalNumber = (number) => Math.floor(number * 100) / 100;
  </script>
@endsection