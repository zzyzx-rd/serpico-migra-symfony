/* global $ curl Chart */
(function() {
  'use strict';

  let graph;
  const graphUrl = $('#graph-url').val();
  const $criterionSelect = $('#criterion-select');
  const crtId = $criterionSelect.val();
  const url = graphUrl.replace('000', crtId);

  function onCriterionChange() {
    const crtId = $criterionSelect.val();
    const graphData = graph.data;
    const url = graphUrl.replace('000', crtId);

    // graph.clear();
    $.post(url).done(({ elementGraphData }) => {
      graphData.datasets[1].data = elementGraphData.averages;
      graphData.datasets[1].hoverBackgroundColor = graphData.datasets[1].backgroundColor = elementGraphData.colors;
      graphData.labels = elementGraphData.names;
      graphData.grandAvg = elementGraphData.grandAverage;
      graph.update();
    });
  }


  $.post(url)
  .done(data => graph = initGraph(data.elementGraphData))
  .fail(err => console.log(err));

  $('#criterion-select').change(onCriterionChange);

  $('.input-field > select').material_select();


  /**
   * @param {} graphData
   * @returns {Chart}
   */
  function initGraph(graphData) {
    if (!graphData) return null;

    const $canvas = $('#graph');
    const grandAvg = graphData.grandAverage;
    const chartData = {
      labels: graphData.names,
      datasets: [
        {
          label: '',
          data: [],
          type: 'line',
          tooltips: {
            enabled: false
          },
          backgroundColor: 'transparent',
          borderColor: 'red',
          datalabels: {
            labels: {
              title: null
            }
          }
        },
        {
          label: 'Performance',
          data: graphData.averages || [],
          backgroundColor: graphData.colors || [],
          hoverBackgroundColor: graphData.colors || [],
          barPercentage: .7,
          datalabels: {
            textShadowColor: '#fff',
            formatter: v => `${v} %`,
            color: '#fff',
            anchor: 'end',
            align: 'bottom',
            rotation: 270
          }
        }
      ],

      set grandAvg(grandAvg) {
        const avgDataset = this.datasets[0];
        const perfDataset = this.datasets[1];

        avgDataset.label = `Moyenne (${grandAvg} %)`;
        avgDataset.data = Array(perfDataset.data.length).fill(grandAvg);
      },
    };

    chartData.grandAvg = grandAvg;

    return new Chart($canvas, {
      type: 'bar',
      data: chartData,
      options: {
        events: [
          'mousemove'
        ],
        elements: {
          point: {
            radius: 0
          }
        },
        legend: {
          position: 'top'
        },
        scales: {
          yAxes: [
            {
              ticks: {
                min: 0,
                max: 100
              }
            }
          ]
        }
      }
    });
  }
})();
