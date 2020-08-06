$(function () {

  card = $('.surveyAnswer').length;
  compteurCanvas=0;
  cardAnswer = $('.field-div').length;
  cardPanel = $('.surveyAnswer');
  field = $('.field');
  bQuest = $('.displayMicro').first().find(".answer");
  graphlist = $('.graph');
  next = 0;
  const color = ["#43a047","#e53935",'#3949ab','#1e88e5','#039be5', '#43a047', '#c0ca33', '#fb8c00', '#6d4c41', 'green', 'gray', 'pink', 'purple', 'whitesmoke', 'yellow', 'brown'];


  fieldlist = $('.field-type');
  fieldIdList = $('.field-id');
  tableId = [];
  fieldIdList.each(function (key, value) {
    tableId.push($(this).attr('id'));
  });
  fieldIdList.each(function (key, value) {
    tableId.push($(this).attr('id'));
  });




  valueTable = [];

  var options = {
    tooltips: {
      enabled: false
    },
    plugins: {
      datalabels: {
        formatter: (value, ctx) => {
          let sum = 0;
          let dataArr = ctx.chart.data.datasets[0].data;
          dataArr.map(data => {
            sum += data;
          });
          let percentage = (value * 100 / sum).toFixed(2) + "%";
          return percentage;
        },
        color: '#fff',
      }
    }
  };

  $(document).find('.byAll').first().css('color', 'white');
  $(document).find('.byAll').first().css('background-color', '#ee6e73');

  $(document).find('.byAll').on('click', function () {
    $(this).css('background-color', '#ee6e73');
    $(this).css('color', 'white');
    $(document).find('.byQ').css('color', '#ee6e73');
    $(document).find('.byQ').css('background-color', 'white');
    $(document).find('.byS').css('color', '#ee6e73');
    $(document).find('.byS').css('background-color', 'white');
  })

  $(document).find('.byQ').on('click', function () {
    $(this).css('background-color', '#ee6e73');
    $(this).css('color', 'white');
    $(document).find('.byAll').css('color', '#ee6e73');
    $(document).find('.byAll').css('background-color', 'white');
    $(document).find('.byS').css('color', '#ee6e73');
    $(document).find('.byS').css('background-color', 'white');
    $(document).find('.change-answer').first().css('background-color', '#ee6e73');
    $(document).find('.change-answer').first().css('color', 'white');
    $(document).find('.change').first().css('background-color', 'white');
    $(document).find('.change').first().css('color', '#ee6e73');
  })

  $(document).find('.byS').on('click', function () {
    $(this).css('background-color', '#ee6e73');
    $(this).css('color', 'white');
    $(document).find('.byAll').css('color', '#ee6e73');
    $(document).find('.byAll').css('background-color', 'white');
    $(document).find('.byQ').css('color', '#ee6e73');
    $(document).find('.byQ').css('background-color', 'white');
    $(document).find('.change').first().css('background-color', '#ee6e73');
    $(document).find('.change').first().css('color', 'white');
    $(document).find('.change-answer').first().css('background-color', 'white');
    $(document).find('.change-answer').first().css('color', '#ee6e73');
  })


  /*
  $(document).find('.tab').on('click', function () {
    $('.tab').each(function () {
      $(this).css('background-color', 'white');
      $(this).find('a').css('color', '#ee6e73');
    })
    $(this).find('a').css('color', 'white');
    $(this).css('background-color', '#ee6e73');
  })
  */

  $('.tabs').tabs();
  $(document).find('.displayMacro').css('display', 'block');
  $(document).find('.byquestions').css('display', 'none');
  $(document).find('.byanswers').css('display', 'none');

  tabsSurvey = '';
  for (u = 0; u < $('.surveyAnswer').length; u++) {
    tabsSurvey += '      <li class="tab tab-survey col s1">\n' +
      '                  <a  class="change" id=' + u + '>' + u + '</a>\n' +
      '                </li>';

  }
  tabsField = '';
  for (u = 0; u < $('.answer-div').length; u++) {
    tabsField += '      <li class="tab tab-answer col s1">\n' +
      '                  <a  class="change-answer" id=' + u + '>' + u + '</a>\n' +
      '                </li>';

  }
  $('.answer-div').each(function (key, value) {
    tabsquestion = $(this).find('.tabs-question');
    tabsquestion.append(tabsField);
  })
  $('.surveyAnswer').each(function (key, value) {
    tabs = $(this).find('.tabs');

    longueur = 0;
    y = 0;

    tabs.append(tabsSurvey)
    valueT = [];

    $(this).find('.answer').each(function (i, val) {
      type = $(this).attr('id');
      id = $(this).find('.answer-id').attr('id');

      if (tableId[y+longueur ] != id) {
        longueur = tableId.indexOf(id) - i;
        y = y + 1;

        for (u = 0; u < longueur; u++) {
          valueT.push(undefined);
        }


      } else {
        y = y + 1;

      }


      switch (type) {
        case "MC":
          var val = $(this).find('p.field-answer');

          vals = [];
          val.each(function (key, value) {
            vals.push($(this).text());
          });
          valueT.push(vals);
          break;
        case "LS":
          var val = $(this).find('.field-answer').attr('id');

          valueT.push(val);
          break;
        case "UC":
          var val = $(this).find('input.field-answer').is(':checked');
          valueT.push(val);
          break;
        case "SC":
          var val = $(this).find('.field-answer').text();
          valueT.push(val);
          break;
        default:
          var val = $(this).find('.field-answer').text();
          valueT.push(val);
          break;
      }
    })

    valueTable.push(valueT);

  });
  fieldlist.each(function (key, value) {
    type = $(this).attr('id');
    num = $(this).parent().attr('id');

    tblField = tableField(num, valueTable);

    if (tblField.length != 0 ) {
      if (type == "UC") {
        tbl = countUC(num, valueTable, tblField.sort());


          tableColor = getTableColorUC(tblField);

      }
      else if (type == "MC") {

        tbl = countMC(num, valueTable, tblField.sort());
        tblCount = tbl[0];
        tblLabel = tbl[1];


          tableColor = getTableColorMC(tblCount);


      }
      else if (type == "LS") {

        tbl = count(num, valueTable, tblField.sort());
        var tblfield = countLS(num, tbl, tblField);

          tableColor = getTableColor(tblfield[0]);



      }
      else if (type == "SC") {

        tbl = count(num, valueTable, tblField.sort());
          tableColor = getTableColor(tblField);



      }



      switch (type) {
        case "MC":
          var ctx = $(this).find('.histo');


          var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: tblLabel,
              datasets: [
                {
                  label: tblLabel,
                  data: tblCount,
                  backgroundColor: tableColor,
                  borderColor: tableColor,


                },]


            },
            options: {
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true,
                    stepSize: 1
                  }
                }]
              },
              legend: {
                labels: {
                  filter: function (legendItem, chartData) {
                    if (legendItem.datasetIndex === 0) {
                      return false;
                    }
                    return true;
                  },
                  display: false,
                }

              }


            } })


          myChart.update();

          break;
        case "LS":
          var ctx = $(this).find('.histo');
          Input = $(document).find('.answer-id')[parseInt(num)];
          min = $(Input).find('.field-radio').first().attr('id');
          var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: tblfield[1],
              datasets: [{
                label:tblfield[1],
                data: tblfield[0],
                backgroundColor: tableColor,
                borderColor: tableColor,
                borderWidth: 1
              }]
            },
            options: {
              legend: {
                labels: {
                  filter: function (legendItem, chartData) {
                    if (legendItem.datasetIndex === 0) {
                      return false;
                    }
                    return true;
                  },
                  display: false,
                }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true,
                    stepSize: 1

                  }
                }]
              },
              tooltips: {
                enabled: false
              },
              plugins: {
                datalabels: {
                  formatter: (value, ctx) => {
                    let sum = 0;
                    let dataArr = ctx.chart.data.datasets[0].data;
                    dataArr.map(data => {
                      sum += data;
                    });
                    let percentage = (value * 100 / sum).toFixed(2) + "%";
                    return percentage;
                  },
                  color: '#fff',
                }
              }
            }

          })
          myChart.defaults.global.legend.display=false;



          myChart.update();
          break;
        case "UC":
          var ctx = $(this).find('.pie');
          var labelsUc = $(document).find('.label-switch');

          const index = tbl.indexOf(0);
          labels = [labelsUc[0].innerHTML , labelsUc[1].innerHTML];

          if (index > -1) {

            tbl.splice(index, 1);
            labels.splice(index, 1);

          }

          console.log(labels);

          var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
              labels: labels,
              datasets: [{
                data: tbl,
                backgroundColor: tableColor,
                borderColor: tableColor,
                borderWidth: 1
              }]
            },
            options: options


          })


          break;
        case "SC":
          var ctx = $(this).find('.pie');

          var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
              labels: tblField,
              datasets: [{
                data: tbl,
                backgroundColor: tableColor,
                borderColor: tableColor,
                borderWidth: 1
              }]
            },
            options: options

          })


          break;


      }
    }
    })
  function* loop(arr) {
    let i = 0;

    while (true) {
      yield arr[i] || arr[i = 0];
      ++i;
    }
  }
  function getRandomColor() {
    var colors = ['#15a6ae', 'red ', 'blue', 'orange', 'lightblue', 'green', 'gray', 'pink', 'purple', 'whitesmoke', 'yellow', 'brown'];
   let color = colors[Math.floor(Math.random() * colors.length)];

    return color;
  }

  function getTableColor(tblField) {
    tblColor = [];
    const loopArr = loop(color);
    for (i = 0; i < tblField.length; i++) {
      let color = loopArr.next().value;



      tblColor.push(color);
    }
    return tblColor;
  }

  function getTableColorUC(tblField) {
    tblColor = [];

    const loopArr = loop(color);
    for (i = 0; i < 2; i++) {
      let color = loopArr.next().value;

      tblColor.push(color);
    }

    return tblColor;
  }

  function getTableColorMC(tblField) {
    tblColor = [];
    const loopArr = loop(color);
    for (i = 0; i < tblField.length; i++) {

      let color = loopArr.next().value;

      tblColor.push(color);
    }

    return tblColor;
  }

  function count(num, tbl, tblField) {
    tableCount = [];

    for (i = 0; i < tblField.length; i++) {

      tableCount.push(0);

    }

    for (i = 0; i < tbl.length; i++) {

      if (tblField.indexOf(tbl[i][num]) != -1) {
        place = tblField.indexOf(tbl[i][num]);
        tableCount[place] = tableCount[place] + 1;
      }


    }

    return tableCount;
  }

  function countLS(num, tbl, tblField) {
    tableCount = [];
    tblReponse=[];
    tableLabel =[];



    Input = $(document).find('.answer-div')[parseInt(num)];
    min = $(Input).find('.field-radio').first().attr('id');
    max = $(Input).find('.field-radio').last().attr('id');


    longueur = parseInt(max) - parseInt(min);

    for (i = parseInt(min); i <= parseInt(max); i++) {
      tableLabel.push(i);
      tableCount.push(0);

    }

    for (i = 0; i < tbl.length; i++) {
      tableCount[tblField[i]-min] = tbl[i];
    }


    tblReponse.push(tableCount);
    tblReponse.push(tableLabel)
    return tblReponse;
  }


  function countMC(num, tbl, tblField) {

    tableCount = [];
    tableDiff = [];

    for (i = 0; i < tblField.length; i++) {


      for (e = 0; e < tblField[i].length; e++) {
        if (tableDiff.indexOf(tblField[i][e]) == -1) {
          tableCount.push(0);
          tableDiff.push(tblField[i][e]);

        }


      }
    }


    for (u = 0; u < tbl.length; u++) {
    if(tbl[u][num]!=null) {


      for (y = 0; y < tbl[u][num].length; y++) {
        if (tableDiff.indexOf(tbl[u][num][y]) != -1) {

          place = tableDiff.indexOf(tbl[u][num][y]);
          tableCount[place] = tableCount[place] + 1;
        }


      }
    }
    }
    tableResult = [];
    tableResult.push(tableCount);
    tableResult.push(tableDiff);
    return tableResult;
  }



})

function countUC(num, tbl, tblField) {
  tableCount = [0, 0];



  for (i = 0; i < tblField.length; i++) {

    if (tbl[i][num] == true) {

      tableCount[0] = tableCount[0] + 1;
    }
    else {

      tableCount[1] = tableCount[1] + 1;

    }


  }

  return tableCount;
}
function tableField(num, tbl) {
  tblField = [];
  for (i = 0; i < tbl.length; i++) {

    if (tblField.indexOf(tbl[i][num]) != -1) {

    } else {
      if (tbl[i][num] != undefined) {
        tblField.push(tbl[i][num]);
      }
    }


  }

  return tblField;
}

var compteur = 0;

$(document).on('click', ' .byAll', function (e) {
  $(document).find('.displayMacro').css('display', 'block');
  $(document).find('.byquestions').css('display', 'none');
  $(document).find('.byanswers').css('display', 'none');
});

$(document).on('click', ' .byQ', function (e) {
  $(document).find('.displayMacro').css('display', 'none');
  $(document).find('.byanswers').css('display', 'none');
  $(document).find('.byquestions').css('display', 'block');
  $(document).find('.byquestions').find('.answer-div').hide();
  $(document).find('.byquestions').find('.answer-div').first().css('display', 'block');
  next=0;
});

$(document).on('click', ' .byS', function (e) {
  $(document).find('.displayMacro').css('display', 'none');
  $(document).find('.byquestions').css('display', 'none');
  $(document).find('.byanswers').css('display', 'none');
  $(document).find('.byanswers').first().css('display', 'block');
  suivant=0;
});

response = $(this).find("#0");
response.show();

$(document).on('click', ' .btnsurvey ', function (e) {
  $(this).closest('.surveyAnswer').hide();
  suivant = $(this).closest('.surveyAnswer').attr('id');
  suivant = parseInt(suivant) + 1;

  if (suivant < card) {
    show = $('.surveyAnswer')[suivant];
    $(show).show();
    $(show).find('.btnprec').show();

    if(suivant==card-1){
      $(show).find('.btnsurvey').hide();
    }
  }
  else {
    show = $('.surveyAnswer').first();
    show.show();
    suivant = 0;
  }
  var number = $(show).closest('.byanswers').find('.tab-survey')[parseInt(suivant)];
  $(number).find('a').css('background-color', '#ee6e73');
  $(number).find('a').css('color', 'white');
  $(number).closest('.tab-survey').css('background-color', '#ee6e73');
  $(number).closest('.tab-survey').css('color', 'white');
})

$(document).on('click', ' .btnprec ', function (e) {
  $(this).closest('.surveyAnswer').hide();
  suivant = parseInt(suivant) - 1;
  show = $('.surveyAnswer')[suivant];
  $(show).show();

  if (suivant == 0) {
    $(show).find('.btnprec').hide();
  }
  var number = $(show).closest('.byanswers').find('.tab-survey')[parseInt(suivant)];
  $(number).find('a').css('background-color', '#ee6e73');
  $(number).find('a').css('color', 'white');
  $(number).closest('.tab-survey').css('background-color', '#ee6e73');
  $(number).closest('.tab-survey').css('color', 'white');
})

$(document).on('click', ' .btnanswerprec ', function (e) {
  $(this).closest('.answer-div').hide();
  show = $('.answer-div')[next - 1];
  $(show).show();
  next = next - 1;

  if (next == 0) {
    $(show).find('.btnanswerprec').hide();
  }
  var number = $(show).closest('.answer-div').find('.tab-answer')[parseInt(next)];
  $(number).find('a').css('background-color', '#ee6e73');
  $(number).find('a').css('color', 'white');
  $(number).closest('.tab-answer').css('background-color', '#ee6e73');
  $(number).closest('.tab-answer').css('color', 'white');
})

$(document).on('click', ' .btnanswer', function (e) {
  $(this).closest('.answer-div').hide();

  if (next < cardAnswer) {
    show = $('.answer-div')[next+1];
    next = parseInt(next) + 1;
    show.style = "display:block";
    $(show).find('.btnanswerprec').show();

    if(next==cardAnswer-1){
      $(show).find('.btnanswer').hide();
    }
  }
  else {
    show = $('.answer-div').first();
    show.show();
    next = 0;
  }
  var number = $(show).closest('.answer-div').find('.tab-answer')[parseInt(next)];
  $(number).find('a').css('background-color', '#ee6e73');
  $(number).find('a').css('color', 'white');
  $(number).closest('.tab-answer').css('background-color', '#ee6e73');
  $(number).closest('.tab-answer').css('color', 'white');
})
$(document).on('click', ' .change', function (e) {
  id = parseInt($(this).attr('id'));

  $('.change').css({
    backgroundColor: 'white',
    color: '#ee6e73'
  });

  $(this).css({
    backgroundColor: '#ee6e73',
    color: 'white'
  });

  $(this).closest('.surveyAnswer').hide();
  cardPanel = $('.surveyAnswer')[id];
  suivant = parseInt(id);
  cardPanel.style = "display:block";
  $(cardPanel).show();
  if(id==card-1){
    $(cardPanel).closest('.surveyAnswer').find('.btnprec').show();
    $(cardPanel).closest('.surveyAnswer').find('.btnsurvey').hide()

  }
  else if (id==0){
    $(cardPanel).closest('.surveyAnswer').find('.btnsurvey').show()
    $(cardPanel).closest('.surveyAnswer').find('.btnprec').hide();

  }
  else{
    $(cardPanel).closest('.surveyAnswer').find('.btnsurvey').show()
    $(cardPanel).closest('.surveyAnswer').find('.btnprec').show();
  }
})

$(document).on('click', ' .change-answer', function (e) {
  const $this = $(this);
  const id = $this.attr('id');
  $this.closest('.answer-div').hide();

  // remove bg on all tabs
  $('.change-answer').css({
    backgroundColor: 'white',
    color: '#ee6e73'
  });
  $('.tab-answer').css({
    backgroundColor: 'white',
    color: '#ee6e73'
  })

  $this.css({
    backgroundColor: '#ee6e73',
    color: 'white'
  });

  cardPanel = $('.answer-div')[id];
  next = parseInt(id);
  cardPanel.style = "display:block";
  $(cardPanel).show();
  if(next == cardAnswer-1){

    $(cardPanel).closest('.answer-div').find('.btnanswer').hide();
    $(cardPanel).closest('.answer-div').find('.btnanswerprec').show();
  }
  else if (next==0) {
    $(cardPanel).closest('.answer-div').find('.btnanswer').show();
    $(cardPanel).closest('.answer-div').find('.btnanswerprec').hide();
  }
  else{
    $(cardPanel).closest('.answer-div').find('.btnanswer').show();
    $(cardPanel).closest('.answer-div').find('.btnanswerprec').show();

  }
})

