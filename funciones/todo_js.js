// pirmero buscar la llave
const app = [];
const app2 = [];
const app3 = [];
let btnSend = document.querySelector('[data-send]'); //por click
let input = document.querySelector('[name="user"]'); //por boton


btnSend.addEventListener('click', () => {
    $('body').loadingModal('show'); //loader
        if(input.value !== ''){
          // validar si hace click
           
          console.log('click..');
          $("#elimnado").remove();
          consultarUser(input.value);
        }
    }, true);

const consultarUser = async (data) =>{
    $('body').loadingModal('show'); //loader
      console.log('===>',data);
     
      // console.log(series);
      try {
        const resp = await axios.post(`funciones/getUserList.php`,{USER:data}); //resp... almacena la peticion
        console.log(resp.data);
        if(resp.data.status === 'OK'){
          console.log(resp.data.message); //manipulas la respuesta que viene en forma de json
          input.value = '';
          let json = resp.data; //contiene los array de arreglo para manipulacion
          // cargar_2(); //traer metodo de carga
          $('body').loadingModal('show');
          renderDragonBalls(json.data); //redibujar las esferas del dragon
          // $("#navbarToggleExternalContent").modal('hide');
        // offcanvas.navbarToggleExternalContent.hide();
        $("#modalimage").collapse('show');
        $('body').loadingModal('hide');
        $("#modalBusqueda").modal('hide');
        $("#modalmenunav").collapse('hide');
        }
        else if(resp.data.status === 'Private'){
        // $("#modalprivado").modal('show');
        modal.modalprivado.show();
        input.value = '';

         $('body').loadingModal('hide'); //ocultarmetodo de precarga
        }
        else {
        $("#modalError").modal('show');
        input.value = '';
         $('body').loadingModal('hide'); //ocultarmetodo de precarga
        }
      } catch (error) {
        console.error(error);
        $("#modalError").modal('show');
      }
    };


////////////////////////////////////////////////////////////////////////////render dragoonbalks
const renderDragonBalls = (data)=>{
  app.push(data);
      am4core.useTheme(am4themes_animated);
      chart = am4core.create("chartdivtree2", am4plugins_forceDirected.ForceDirectedTree);
      chart.legend = new am4charts.Legend();
      var series = chart.series.push(new am4plugins_forceDirected.ForceDirectedSeries());

series.nodes.template.events.on("hit", function(event) {
      // const nuevo = event.target.dataItem.name;
      
      // console.log(event.target.dataItem.level); 
      if(event.target.dataItem.level ==2){
        event.target.isActive = true; 
        // console.log('event.2',event.target.dataItem);
        // console.log('event.2',event.target.dataItem.dataContext.relacion);
        // consultaModal(event.target.dataItem.name,event.target.dataItem.dataContext.relacion);
        consultaModal(event.target.dataItem.dataContext);
      }
      });

// #0b3954, #087e8b, #bfd7ea, #ff5a5f, #c81d25
// paletecolor = ["0b3954","087e8b","bfd7ea","ff5a5f","c81d25"];
//#00bbf9 #ff6b6b #ffe66d
//
paletcolorchildren = ["00bbf9","ff6b6b","ffe66d"];
      series.data = app; //mandamos la variable app con los agregado o no antes de hacer el render
      series.dataFields.name = "name";
      series.dataFields.id = "name";
/////////////////////////////////////////////////////////////////////////////////// modifcacion 17/01      
      // series.dataFields.value = "logXs";
      series.dataFields.value = "value";
      series.dataFields.followers = "value";
      series.dataFields.friends = "friends";
series.dataFields.relacion = "relacion";
series.dataFields.children = "children";
series.contextMenuDisabled = true; //deshablitar el click derecho en los elementos


series.manyBodyStrength = -15;

  ////valores para linea de union entre esferas
series.links.template.strength = .2; //cercania de las esferas
series.links.template.strokeOpacity =0; //opacidad para union de esferas
 series.links.template.strokeWidth = 1; //////////////validar



series.nodes.template.label.text = "[bold]{name}";
series.fontSize = 15;

      series.minRadius = 30;
      series.maxRadius = 150;
      // series.minRadius = am4core.percent(1);
      // series.maxRadius = am4core.percent(16);

      series.maxLevels = 2;


  ///propiedades para los nodos
      var nodeTemplate = series.nodes.template;
      series.nodes.template.label.hideOversized = false;
      series.nodes.template.label.truncate = false;
  
      // series.nodes.template.label.text = "[bold]{name}[/]\n{valueY}";
        // series.nodes.template.label.text = series.data.burbuja;
        series.nodes.template.label.valign = "bottom";
        series.nodes.template.label.fontSize = 14;
        series.nodes.template.label.fill = am4core.color("white");
        series.nodes.template.label.dy = 15; //cercania del nombre a la esfera
       // series. am4core.percent(100);
                  series.nodes.template.adapter.add("tooltipText", function(text, target) {
                      if (target.dataItem) {
                        switch(target.dataItem.level) {
                          case 0:
                            return "[bold]@{name}[/]\n Total Cuentas: [bold]{followers}[/]\n";
                          case 1:
                            // return "[bold]{name}[/]\n Total: [bold]{value}[/] \n";
                            return "[bold]{name}[/]\n";

                            case 2:
                            return "[bold]{name}[/]\n Seguidores: [bold]{followers}[/]\n Seguidos: [bold]{friends}[/]\n";
                          case 3:
                            return "[bold]{name}[/] ";

                        }
                      }
                      return text;
                      });

// asignar colores alas esfeeeeraaas


series.nodes.template.adapter.add("fill", function(fill, target) {
   if(target.dataItem.level == 2){
     
     // target.fill = "#"+ paletecolor [Math.round(Math.log(target.dataItem.value))];
     // target.fill = "#"+ paletcolorchildren[0];
     if(target.dataItem.acolor == "t1"){
       target.fill = "#"+ paletcolorchildren[0];
       // target.fill = red;
       // console.log('soy color1'+target.fill);
       target.fillOpacity = 1.15;
       ////////////////////////////////
       // target.fillOpacity = 0.045;
      }
      else if(target.dataItem.acolor == "t2"){
       target.fill = "#"+ paletcolorchildren[1];
        // target.fill = blue;
       // console.log('soy color2'+target.fill);
       target.fillOpacity = 0.85;
       ////////////////////////////////
       // target.fillOpacity = 0.55;
      }
      else if(target.dataItem.acolor == "t3"){
       target.fill = "#"+ paletcolorchildren[2];
        // target.fill = green;
       // console.log('soy color.'+target.fill);
       target.fillOpacity = 0.85;
       ////////////////////////////////
       // target.fillOpacity = 0.55;
      }
  }

  return fill;
});

////////////////////////////////////////////////////////////////////////////
icon = series.nodes.template.createChild(am4plugins_bullets.PinBullet);
            icon.image = new am4core.Image();
            icon.image.propertyFields.href = "image";

           
            icon.circle.radius = am4core.percent(90);
            icon.circle.strokeWidth = 2; //SEPARACION OCN OTROS OBJETOS

////////////////tamaño menor 30, tamaño mayor 400 de la esferas del dragon

            icon.background.pointerLength = 0;
            icon.background.disabled = true; 
/////////////////////////////////////////////////////////////////////////////////// modifcacion 17/01    
// icon.background.adapter.add("radius", function(radius, target) {   
            icon.background.adapter.add("final", function(radius, target) {   

             // final =10 * target.dataItem.value; //funciona para logaritmo pero no para imagen
               final = 10 * target.dataItem.value; //

              return final;
            });
  
//////////////////////////////////////////

      // para habilitar el zoom
    chart.zoomable = true;
///////////////////////////////////ultimo agregado///////////////////////////////////////
// icon.isDynamic = true;
// icon.isMeasured = true;
//     chart.events.on("maxsizechanged", function(){
//   icon.clones.each(function(clone){
//     clone.radius = clone.radius;
//   })
// });
//     icon.adapter.add("radius", function(radius, target) {
//   var radius = target.parent.outerCircle.pixelRadius;
//   target.width = radius * 2;
//   target.height = radius * 2;

//   target.chartContainer.minWidth = undefined;
//   target.chartContainer.minHeight = undefined;
//  // final = 2 * radius;
//     return radius;
//     // return final;

// });
////////////////////////////////////////////

}; //final del metodo



//////////////////////////////////////////////////////////////////////////////////////////

////////////////////////UNA VEZ CREADA LA ESFERA PROCEDEMOS AL HACER CLICK CREAMOS LA TABLA///////////


    const consultaModal = async (data2) =>{
    $('body').loadingModal('show'); //loader
      try {
        if(data2.protected === 'Publico'){
          input.value = '';
          let json2 = data2;
          // console.log('soyconsultamodal'+json2);
          creaciontabla(json2);
          generandomodal(data2.resmodal);
          $("#offcanvastable").offcanvas("show");
          $('body').loadingModal('hide');
        }
        else if(data2.protected === 'Protegido'){
         $("#offcanvastable").offcanvas("hide");
         $("#modalprivado").modal('show');
        input.value = '';
         $('body').loadingModal('hide'); //ocultarmetodo de precarga
        }
        else {
        $("#modalError").modal('show');
        input.value = '';
         $('body').loadingModal('hide'); //ocultarmetodo de precarga
        }
      } catch (error) {
        console.error(error);
        $("#modalError").modal('show');
      }
    };

    //////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////CONSTANTE CREACION TABLA///////////////////////////////////////////////////

const creaciontabla = (data3,rela)=>{
app3.pop();
app3.push(data3);

jimage = app3[0].image;
jname = app3[0].name;
jfollwers = app3[0].value;
jfriends = app3[0].friends;
jlocation = app3[0].location;
jstatus_count = app3[0].statuses_count;
jfecha = app3[0].FECHA;
jendtweet = app3[0].endtweet;
jdatetweet = app3[0].twt_created_at;
jdaterecord = app3[0].daterecord;
jrt = app3[0].rt;
jlike = app3[0].like;
jtiporela = app3[0].relacion;
// var javasrult = (10 - 5);
// document.getElementById('lbjimage').innerHTML = jimage;
document.getElementById('lbjname').innerHTML ="@"+jname;
document.getElementById('lbjfollwers').innerHTML = jfollwers;
document.getElementById('lbjfriends').innerHTML = jfriends;
document.getElementById('lbjlocation').innerHTML = jlocation;
document.getElementById('lbjstatus_count').innerHTML = jstatus_count;
document.getElementById('lbjendtweet').innerHTML = jendtweet;
document.getElementById('lbjdatetweet').innerHTML = jdatetweet;
document.getElementById('lbjfecha').innerHTML = jfecha;
document.getElementById("myImg").src = jimage;
document.getElementById('lbjdaterecord').innerHTML = jdaterecord;

document.getElementById('lbjrt').innerHTML = "RT: "+jrt;
document.getElementById('lbjlike').innerHTML ="LIKE: "+ jlike;
document.getElementById('refximg').setAttribute('href', jimage);
document.getElementById('lbjtiporela').innerHTML = jtiporela;
};
/////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////GENERAL MODAL ENSEGUIDA//////////////////////////////////////////////////////
const generandomodal = (data2)=>{
app2.pop();
app2.push(data2);
// 
// var app2 = data2;

am4core.useTheme(am4themes_animated);
var chart2 = am4core.create("chartdivmodal", am4charts.PieChart);

// chart2.data = [
//   {
//     country: "Lithuania",
//     value: 401
//   },
//   {
//     country: "Czech Republic",
//     value: 300
//   }];

chart2.hiddenState.properties.opacity = 0;
chart2.data = data2;
chart2.radius = am4core.percent(70);
chart2.innerRadius = am4core.percent(40);
chart2.startAngle = 180;
chart2.endAngle = 360;  

var series2 = chart2.series.push(new am4charts.PieSeries());
series2.isMeasured = false;
// series2.propertyFields.data = "pie2";
series2.dataFields.category = "parametro";
series2.dataFields.value = "value";


series2.slices.template.cornerRadius = 10;
series2.slices.template.innerCornerRadius = 7;
series2.slices.template.draggable = false;
series2.slices.template.inert = true;
series2.alignLabels = false;

series2.hiddenState.properties.startAngle = 90;
series2.hiddenState.properties.endAngle = 90;

chart2.legend = new am4charts.Legend();

}; //final de la constaste generandomodal

///////////////////////////MODIFICACION DE RANGO//////////////////////////////////////////////////////////////////

  $(document).on('change', 'input[type="range"]', function() {

            var valorBase = $('#rango').val();
            var calculo = Math.round((valorBase * 2.3) / 100);
            $('#calculo').html(total);
            total = parseInt(valorBase) + parseInt(calculo);
            $('#total').html(total);
            tenden1 = total;
        });


////////////////////////BOTONES PARA GENERAR DESPUES DE CREAR TABLA//////////////////////////////////////////
let run_modal2 = document.querySelector('[btn-run-modal]');
run_modal2.addEventListener('click', () => {
    $('body').loadingModal('show'); //loader
    $("#modal_twets_opcion").modal('show');
      $('body').loadingModal('hide'); //loader
        // }
    }, true);
////////////////////////////////////////////////////////////////////
  const app6 = [];
let btnSend2 = document.querySelector('[data-send2]'); //por click
  // let input2 = document.querySelector('[id="jname"]'); //por boton
btnSend2.addEventListener('click', () => {
    // $('body').loadingModal('show'); //loader
    console.log(jname);
      consultarUser(jname);
        // }
    }, true);

let btnget = document.querySelector('[data-update]');  
let btnget7 = document.querySelector('[data-t7d]');  
let btnget30 = document.querySelector('[data-t30d]');

// aactulizar registro
btnget.addEventListener('click', () => {
    console.log('click1');
    updateProfile(jname,2,jtiporela);
    
    }, true);
// twwets ultimos 7 dias
btnget7.addEventListener('click', () => {
    // console.log('click2');
    console.log('click2..jname: '+jname+' total: '+tenden1);
    consulTimeline(jname,3,tenden1);
    }, true);
//tweets poppulares
btnget30.addEventListener('click', () => {
    // console.log('click3');
    console.log('click3..jname: '+jname+' total: '+tenden1);
    consulTimeline(jname,4,tenden1);
    }, true);

const updateProfile = async (data,guia) =>{
      // console.log('===>',data);
      try {
        const resp = await axios.post(`funciones/getTimeline.php`,{USER:data,DIREC:guia,RELA:jtiporela}); //resp... almacena la peticion
        if(resp.data.status === 'OK'){
        // console.log(resp.data.message); //manipulas la respuesta que viene en forma de json
        // console.log(resp.data.update);
        // console.log(resp.data.update.resmodal); //manipulas la respuesta que viene en forma de json
        // $("#offcanvastable").offcanvas("hide");
        creaciontabla(resp.data.update);
        generandomodal(resp.data.update.resmodal);
        // $("#offcanvastable").offcanvas("show");
        }
      } catch (error) {
        console.error(error);
        $("#modalError").modal('show');
      }
    };


const consulTimeline = async (data,guia,rang) =>{
      // console.log('===>',data);
      try {
        const resp2 = await axios.post(`funciones/getTimeline.php`,{USER:data,DIREC:guia,RELA:rang}); //resp... almacena la peticion
        // console.log(resp2.data.datalast);
              // if(resp2.data.status === 'OK'){
              //   console.log(resp2.data.message); //manipulas la respuesta que viene en forma de json
              //   input.value = '';
              //   let json8 = resp2.data; //contiene los array de arreglo para manipulacion
              //   console.log('devuelta===>',resp2.data.data);
              //   runmodaltweets();
              //   $("#modaltweets").modal('show');
              // }
    //////////////////////////////////////////////////////////////////////////////////          
              if(resp2.data.status === 'OK_last'){
                console.log(resp2.data.message); //manipulas la respuesta que viene en forma de json
                input.value = '';
                let json8 = resp2.data; //contiene los array de arreglo para manipulacion
                console.log('devuelta last===>',resp2.data.datalast);
                runmodaltweets(resp2.data.datalast);
                $("#modaltweets").modal('show');
              }
              else if(resp2.data.status === 'OK_populares'){
                console.log(resp2.data.message); //manipulas la respuesta que viene en forma de json
                input.value = '';
                let json8 = resp2.data.data; //contiene los array de arreglo para manipulacion
                console.log('devuelta popular===>',resp2.data.datapopular);
                runmodaltweets(resp2.data.datapopular);
                $("#modaltweets").modal('show');
              }
              else if(resp2.data.status === 'Empty'){
              // modal.modalvacio.show();
              $("#modalvacio").modal('show');
              input.value = '';
              $('body').loadingModal('hide'); //ocultarmetodo de precarga
              }
              else {
              $("#modalError").modal('show');
              input.value = '';
               $('body').loadingModal('hide'); //ocultarmetodo de precarga
              }
      } catch (error) {
        console.error(error);
        $("#modalError").modal('show');
      }
    };
/////////////////////////////////DESPUES BOTON PARA GENERAR RUN MODAL///////////////////////////////////////////
const runmodaltweets = async (data) =>{
app6.pop();
app6.push(data);
// console.log(app6);
// am4core.ready(function() {
// // Themes begin
// am4core.useTheme(am4themes_animated);
// // Themes end
// // Create chart instance
// var chart = am4core.create("chartdivtweets", am4charts.XYChart);
// chart.data = "";
// chart.data = data;
// // Add data
// // chart.data = [{
// //   "tweet_text": "holaaaaa",
// //   "fecha_creacion": "1 Oct 2022",
// //   "Cretweet": 700
// // }, {
// //     "tweet_text": "holaaaaa2",
// //   "fecha_creacion": "13 Oct 2022",
// //   "Cretweet": 0
// // }];
// // tweet_text
// // fecha_creacion
// // Cretweet

// // Create axes
// var dateAxis = chart.xAxes.push(new am4charts.DateAxis());

// // Create value axis
// var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// // Create series
// var lineSeries = chart.series.push(new am4charts.LineSeries());
// lineSeries.dataFields.valueY = "Cretweet";
// lineSeries.dataFields.dateX = "fecha_creacion";
// lineSeries.dataFields.name = "tweet_text";
// lineSeries.name = "tweet_text";
// lineSeries.dataFields.tipo = "tipo";
// lineSeries.strokeWidth = 3;


// var circleBullet = lineSeries.bullets.push(new am4charts.CircleBullet());
// circleBullet.circle.stroke = am4core.color("orange");
// circleBullet.circle.strokeWidth = 2;
// circleBullet.tooltipText = "[bold]Tweet:[/] {name}\n [bold]Tipo:[/] {tipo}\n";
// // circleBullet.tooltip.maxWidth = 50;
// // circleBullet.tooltip.wrap = true;
// circleBullet.background.radius = 20; //tamaño del circulo
// circleBullet.circle.radius = 30;

// });

// /////////////////////////////////////////////////////////////////////////////////////////

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

var chart = am4core.create("chartdivtweets", am4plugins_timeline.SerpentineChart);
chart.curveContainer.padding(100, 20, 50, 20);
chart.levelCount = 3;
chart.yAxisRadius = am4core.percent(20);
chart.yAxisInnerRadius = am4core.percent(2);
chart.maskBullets = false;

var colorSet = new am4core.ColorSet();

chart.dateFormatter.inputDateFormat = "yyyy-MM-dd HH:mm";
chart.dateFormatter.dateFormat = "HH:mm";

// chart.data = app6;
chart.data = data;

// chart.data = [

//   ];

chart.fontSize = 10;
chart.tooltipContainer.fontSize = 15;

var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "category";
categoryAxis.renderer.grid.template.disabled = true;
categoryAxis.renderer.labels.template.paddingRight = 25;
categoryAxis.renderer.minGridDistance = 10;

var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
dateAxis.renderer.minGridDistance = 70;
dateAxis.baseInterval = { count: 30, timeUnit: "minute" };
dateAxis.renderer.tooltipLocation = 0;
dateAxis.renderer.line.strokeDasharray = "1,4";
dateAxis.renderer.line.strokeOpacity = 0.5;
dateAxis.tooltip.background.fillOpacity = 0.2;
dateAxis.tooltip.background.cornerRadius = 5;
dateAxis.tooltip.label.fill = new am4core.InterfaceColorSet().getFor("alternativeBackground");
dateAxis.tooltip.label.paddingTop = 7;
dateAxis.endLocation = 0;
dateAxis.startLocation = -0.5;

var labelTemplate = dateAxis.renderer.labels.template;
labelTemplate.verticalCenter = "middle";
labelTemplate.fillOpacity = 0.4;
labelTemplate.background.fill = new am4core.InterfaceColorSet().getFor("background");
labelTemplate.background.fillOpacity = 1;
labelTemplate.padding(7, 7, 7, 7);

var series = chart.series.push(new am4plugins_timeline.CurveColumnSeries());
series.columns.template.height = am4core.percent(15);

series.dataFields.openDateX = "start";
series.dataFields.dateX = "end";
series.dataFields.categoryY = "category";
series.baseAxis = categoryAxis;
series.columns.template.propertyFields.fill = "color"; // get color from data
series.columns.template.propertyFields.stroke = "color";
series.columns.template.strokeOpacity = 0;
series.columns.template.fillOpacity = 0.6;
///////agregado
series.dataFields.crt = "Cretweet";
series.dataFields.clk = "Cfavorite";



var imageBullet1 = series.bullets.push(new am4plugins_bullets.PinBullet());
imageBullet1.locationX = 1;
imageBullet1.propertyFields.stroke = "color";
imageBullet1.background.propertyFields.fill = "color";
imageBullet1.image = new am4core.Image();
imageBullet1.image.propertyFields.href = "icon";
imageBullet1.image.scale = 0.5;
imageBullet1.circle.radius = am4core.percent(100);
imageBullet1.dy = -5;
///////////////agregado/////////////////
imageBullet1.tooltipText = "{text}\n[bold]Retweet:[/] {crt}\n[bold]Like:[/] {clk} ";

// var textBullet = series.bullets.push(new am4charts.LabelBullet());
// textBullet.label.propertyFields.text = "text";
// textBullet.disabled = true;
// textBullet.propertyFields.disabled = "textDisabled";
// textBullet.label.strokeOpacity = 0;
// textBullet.locationX = 1;
// textBullet.dy = - 100;
// textBullet.label.textAlign = "middle";

chart.scrollbarX = new am4core.Scrollbar();
chart.scrollbarX.align = "center";
chart.scrollbarX.width = am4core.percent(75);
chart.scrollbarX.opacity = 0.5;

var cursor = new am4plugins_timeline.CurveCursor();
chart.cursor = cursor;
cursor.xAxis = dateAxis;
cursor.yAxis = categoryAxis;
cursor.lineY.disabled = true;
cursor.lineX.strokeDasharray = "1,4";
cursor.lineX.strokeOpacity = 1;

dateAxis.renderer.tooltipLocation2 = 0;
categoryAxis.cursorTooltipEnabled = false;


var label = chart.createChild(am4core.Label);
label.text = "TWEETS"
label.isMeasured = false;
label.y = am4core.percent(15);
label.x = am4core.percent(50);
label.horizontalCenter = "middle";
label.fontSize = 20;

}); // end am4core.ready()

}; //aqui termina metodo.
///////////////////////////////////////////////////////////////////