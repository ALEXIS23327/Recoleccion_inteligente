// Código JavaScript para consumir la API y mostrar los sensores en la sección correspondiente
document.addEventListener('DOMContentLoaded', function() {
    fetchSensors();
  });
  
  function fetchSensors() {
    fetch('api/api.php?action=getSensors')
      .then(response => response.json())
      .then(data => {
        displaySensors(data);
      })
      .catch(error => console.error('Error al obtener los sensores:', error));
  }
  
  function displaySensors(sensors) {
    const container = document.getElementById('sensorContainer');
    container.innerHTML = '';
  
    sensors.forEach(sensor => {
      const card = document.createElement('div');
      card.className = 'card';
      card.innerHTML = `
        <h3>Sensor ID: ${sensor.id_sensor}</h3>
        <p><strong>Ubicación:</strong> ${sensor.ubicacion}</p>
        <p><strong>Tipo:</strong> ${sensor.tipo_sensor}</p>
        <p><strong>Estado:</strong> ${sensor.estado}</p>
        <p><strong>Instalado:</strong> ${sensor.fecha_instalacion}</p>
      `;
      container.appendChild(card);
    });
  }
  
