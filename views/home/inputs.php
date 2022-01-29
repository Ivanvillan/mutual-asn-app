<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.3/xlsx.full.min.js"></script>
    <title>Mutual ASN | Ingreso de planilla</title>
    <style>
        .margin-b{
            margin-top: 30px !important;
        }
        .container{
            margin-left: 300px !important;
        }
    </style>
</head>
<body>
    <?php include('../header/header.php') ?>
    <div class="container">
        <div class="row">
            <h4>INGRESO DE DATOS</h4>
            <div class="col s12 m4 l4">
                <div class="input-field">
                    <select name="" id="offices">
                        <option value="0" disabled selected>Sucursal</option>
                    </select>
                </div>
            </div>
            <div class="col s12 m4 l4">
                <div class="input-field">
                    <select name="" id="products">
                        <option value="0" disabled selected>Producto</option>
                    </select>
                </div>
            </div>
            <div class="col s12 m4 l4">
                <div class="input-field">
                    <select name="" id="Movement">
                        <option value="" disabled selected>Tipo de movimiento</option>
                        <option value="1">Débito</option>
                        <option value="2">Rechazo</option>
                        <option value="3">No Enviado</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m5 l5">
                <form action="#">
                    <div class="file-field input-field">
                        <div class="btn teal darken-4">
                            <span>Planilla</span>
                            <input type="file" id="fileUpload" accept=".xls,.xlsx">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col s12 m6 l6 margin-b">
                <a href="#modify-modal" class="btn btn-modify modal-trigger right blue-grey darken-4">Modificar</a>
            </div>
            <div class="col s12 m1 l1 right margin-b">
                <button class="btn excelUpload blue darken-2">Subir</button>
                <div class="preloader-wrapper hide small active">
                    <div class="spinner-layer spinner-red-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                        <div class="circle"></div>
                    </div><div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l12">
                <table class="highlight responsive-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Legajo</th>
                            <th>DNI</th>
                            <th>Periodo</th>
                            <th>Alta</th>
                            <th>Cobrado</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
        <div id="modify-modal" class="modal">
            <div class="modal-content">
                <div class="row form-modify">
                    <form id="form-modify">
                        <h5 class="col s11 m11 l11 margin-h5">MODIFICAR VALOR COBRADO</h5>
                        <a class="col s1 m1 l1 right modal-close margin-a"><i class="material-icons" style="color: #000 !important;">close</i></a>
                        <div class="col s12 m12 l12 divider" style="margin-bottom: 20px !important;"></div>
                        <div class="col s12 m8 l8 offset-m2 offset-l2 input-field">
                            <input type="number" name="valToModify" id="valToModify"></input>
                            <label for="valToModify">Valor a modificar</label>
                        </div>
                        <div class="col s12 m8 l8 offset-m2 offset-l2 input-field">
                            <input type="number" name="valModify" id="valModify"></input>
                            <label for="valModify">Nuevo valor</label>
                        </div>
                        <div class="modal-footer col s12 m12 l12" style="padding-top: 20px !important;">
                            <a href="#!" class="btn right newModify blue darken-2">Aceptar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        var result = {"rows": []};
        var selectedProduct;
        var selectedOffice;
        var selectedMov;
        var valToReplace = 0;
        var valReplace = 0;
        $(document).ready(function(){
            getProducts();
            $("#products").change(function(){
                selectedProduct = $(this).children("option:selected").val();
                console.log(selectedProduct);
            });
            $("#offices").change(function(){
                selectedOffice = $(this).children("option:selected").val();
                console.log(selectedOffice);
            });
            $("#Movement").change(function(){
                selectedMov = $(this).children("option:selected").val();
                console.log(selectedMov);
            });
            $('.modal').modal();
        });
        // 
        function getProducts(){
            // 
            $.ajax({
                type: "GET",
                url: "http://localhost/mutualasn-api/public/entities/get/products",
                dataType: "json",
                success: function (response) {
                    let rows = response.result;
                    let html = [];
                    for (let i=0; i < rows.length; i++){
                        html.push(
                            `<option value="${rows[i].CodProducto}">${rows[i].Descripcion}</option>`
                        );
                    }    
                    $('#products').append(html.join(''));
                    $('select').formSelect();
                },
                error: function(){
                    console.log('error');
                }
            });
            // 
            $.ajax({
                type: "GET",
                url: "http://localhost/mutualasn-api/public/entities/get/offices",
                dataType: "json",
                success: function (response) {
                    let rows = response.result;
                    let html = [];
                    for (let i=0; i < rows.length; i++){
                        html.push(
                            `<option value="${rows[i].CodSucursal}">${rows[i].NombreSuc}</option>`
                        );
                    }    
                    $('#offices').append(html.join(''));
                    $('select').formSelect();
                },
                error: function(){
                    console.log('error');
                }
            });
            // 
        }
        // 
        $("#fileUpload").change(function(evt){
            var selectedFile = evt.target.files[0];
            var reader = new FileReader();
            reader.onload = function(event) {
                var data = event.target.result;
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });
            workbook.SheetNames.forEach(function(sheetName) {
                XL_row_object = XLS.utils.sheet_to_json(workbook.Sheets[sheetName],{
                    raw: false,
                    header: 0,
                    blankrows: true,
                    defval: "0"
                });
                let row = XL_row_object;
                let html = [];
                for (let i=0; i < row.length; i++){
                    var leg = row[i].Legajo.toString();
                    if (leg.charAt(0) === 'R' || "J") {
                        leg = leg.substring(1);
                    };
                    var sal = row[i].Saldo;
                    if (sal != undefined){ 
                        sal = sal.replace(/,/g, ""); 
                    };
                    var imp = row[i].Importe;
                    if (imp != undefined){ 
                        imp = imp.replace(/,/g, ""); 
                    };
                    var cob = row[i].Cobrado;
                    if (cob != undefined){ 
                        cob = cob.replace(/,/g, ""); 
                    };
                    if (cob === valToReplace) {
                        cob = valReplace;
                    }
                    html.push(
                    `<tr>
                    <td>${row[i].Nombre}</td> 
                    <td>${leg}</td> 
                    <td>${row[i].DNI}</td> 
                    <td>${row[i].Periodo ?? ''}</td> 
                    <td>${row[i].Alta}</td> 
                    <td>${cob ?? 0.00}</td> 
                    </tr>`
                    );
                    result.rows[i] = {"rep": row[i].Rep ?? "0", "solicitud": row[i].Solicitud ?? "0", "periodo": row[i].Periodo ?? "0",
                        "nombre": row[i].Nombre ?? "0", "legajo": leg ?? "0", "alta": row[i].Alta ?? "0", "saldo": sal ?? "0",
                        "importe": imp ?? "0", "cobrado": cob ?? "0", "cuotas": row[i].Cuotas ?? "0",
                        "cuotaspendientes": row[i].CuotasPendientes ?? "0", "conveniocobro": row[i].ConvenioCobro ?? "0", 
                        "dni": row[i].DNI ?? "0", "cbu": row[i].CBU ?? "0", "rech": row[i].Rech ?? "0"
                    };
                }    
                $('table>tbody').html(html.join(''));
                })
            };
            reader.onerror = function(event) {
                console.error("File could not be read! Code " + event.target.error.code);
            };
            reader.readAsBinaryString(selectedFile);
        });
        // 
        $('.excelUpload').click(function (e) { 
            e.preventDefault();
            $('.excelUpload').addClass('hide');
            $('.preloader-wrapper').removeClass('hide');
            var url = "http://localhost/mutualasn-api/public/movements/register/"
            $.ajax({
                type: "POST",
                url: url + selectedOffice + "/" + selectedProduct + "/" + selectedMov,
                data: result,
                dataType: "json",
                success: function (response) {
                    M.toast({html: 'Información cargada correctamente'});
                    $('.excelUpload').removeClass('hide');
                    $('.preloader-wrapper').addClass('hide');
                    location.reload();
                },
                error: function(){
                    M.toast({html: 'Error al cargar información, compruebe los datos'});
                    $('.excelUpload').removeClass('hide');
                    $('.preloader-wrapper').addClass('hide');
                }
            });
        });
        // 
        $('.newModify').click(function (e) { 
            e.preventDefault();
            valToReplace = $("input[name='valToModify']").val() + '.00';
            valReplace = $("input[name='valModify']").val() + '.00';
            $("#form-modify").trigger("reset");
        });
    </script>
</body>
</html>