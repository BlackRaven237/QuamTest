// Simulated API Data matching database structure: id, titre, description, prix
const sampleServices = [
    { id: 1, titre: "Web", description: "Création de sites web modernes, réactifs et sur-mesure.", icon: "code_xml", prix: 200 },
    { id: 2, titre: "Mobile", description: "Développement d'applications mobiles iOS & Android performantes.", icon: "mobile_code", prix: 400 },
    { id: 3, titre: "Logiciel", description: "Conception de logiciels métier adaptés à vos processus.", icon: "desktop_windows", prix: 400 },
    { id: 4, titre: "Maintenance", description: "Suivi technique, mises à jour et optimisation continue.", icon: "construction", prix: 150 }
];

// Function to dynamically render service cards
function renderServices(services) {
    const container = document.getElementById('services-container');
    container.innerHTML = services.map(service => `
        <div class="card">
            <span class="material-symbols-outlined">${service.icon}</span>
            <h3>${service.titre}</h3>
            <p>${service.description}</p>
        </div>
    `).join('');
}

// Simulating CRUD API fetch on load
document.addEventListener('DOMContentLoaded', () => {
    renderServices(sampleServices);
});

// Contact Form Handling
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const status = document.getElementById('form-status');
    status.style.display = 'block';
    status.style.color = '#16a34a';
    status.innerText = "Message envoyé avec succès !";

    this.reset();
    setTimeout(() => {
        status.style.display = 'none';
    }, 4000);
});