<?php
/**
 * Text Generator for Veterinary Services
 * Generates dynamic, SEO-friendly content for Service + Location pages
 */

function generateServiceLocationContent($service, $location, $locationType)
{
    // Basic data preparation
    $serviceName = $service['nome'];
    $serviceNameLower = strtolower($serviceName);
    $locationName = $location['nome'];

    // Proper preposition and location formatting
    if ($locationType === 'comune') {
        $locationPrep = 'a';
        $locationFull = $locationName;
        $locationIn = "nel comune di {$locationName}";
    } elseif ($locationType === 'provincia') {
        $locationPrep = 'in';
        $locationFull = "provincia di {$locationName}";
        $locationIn = "in provincia di {$locationName}";
    } else { // regione
        $locationPrep = 'in';
        $locationFull = $locationName;
        $locationIn = "in {$locationName}";
    }

    // Get base content from service and adapt it
    $baseContent = !empty($service['contenuto']) ? $service['contenuto'] : '';

    // If we have base content, localize it
    if (!empty($baseContent)) {
        // Add location-specific introduction
        $locationIntro = "<div class='location-intro' style='background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 2rem; border-radius: 8px; margin-bottom: 2rem; border-left: 4px solid var(--accent-color);'>
            <h3 style='color: var(--primary-color); margin-top: 0;'>Servizio specializzato in {$serviceName} {$locationPrep} {$locationFull}</h3>
            <p style='font-size: 1.1rem; margin-bottom: 0;'>Stai cercando un veterinario specializzato in <strong>{$serviceNameLower}</strong> {$locationIn}? La nostra rete di professionisti qualificati è presente sul territorio e pronta ad assisterti con competenza e dedizione.</p>
        </div>";

        // Add location-specific additional content
        $locationDetails = generateLocationSpecificDetails($serviceName, $serviceNameLower, $locationFull, $locationName, $locationType);

        // Combine: location intro + base content + location details
        return $locationIntro . $baseContent . $locationDetails;
    }

    // Fallback: if no base content, generate full content
    return generateFullServiceLocationContent($serviceName, $serviceNameLower, $locationFull, $locationName, $locationIn, $locationPrep, $locationType);
}

function generateLocationSpecificDetails($serviceName, $serviceNameLower, $locationFull, $locationName, $locationType)
{
    $locationLabel = $locationType === 'provincia' ? "della provincia di {$locationName}" : "di {$locationName}";

    return "
    <div class='location-specific-content' style='margin-top: 3rem; padding-top: 2rem; border-top: 2px solid #e2e8f0;'>
        <h3 style='color: var(--primary-color);'>Copertura territoriale {$locationLabel}</h3>
        <p>Il nostro servizio di <strong>{$serviceNameLower}</strong> copre l'intero territorio {$locationLabel}, garantendo interventi rapidi e professionali. Collaboriamo con veterinari esperti che conoscono le specificità del territorio locale e sono in grado di offrire assistenza tempestiva in caso di emergenze.</p>
        
        <p>Che tu abbia bisogno di una visita di routine, un intervento specialistico o assistenza urgente, il nostro network professionale {$locationLabel} è sempre disponibile. La vicinanza geografica ci permette di ridurre i tempi di intervento e offrire un servizio più personalizzato, conoscendo le caratteristiche ambientali e sanitarie della zona.</p>
        
        <div style='background: #f0f9ff; padding: 1.5rem; border-radius: 6px; margin: 1.5rem 0; border-left: 3px solid var(--primary-color);'>
            <p style='margin: 0; font-weight: 600; color: var(--primary-color);'>📍 Servizio attivo su tutto il territorio {$locationLabel}</p>
            <p style='margin: 0.5rem 0 0 0;'>Veterinari qualificati, interventi rapidi, assistenza H24 per emergenze</p>
        </div>
        
        <h3 style='color: var(--primary-color); margin-top: 2rem;'>Perché scegliere i nostri veterinari {$locationLabel}?</h3>
        <ul style='line-height: 1.8; color: var(--text-muted);'>
            <li><strong>Conoscenza del territorio:</strong> I nostri professionisti operano stabilmente {$locationLabel} e conoscono le peculiarità locali</li>
            <li><strong>Rete capillare:</strong> Collaborazioni con cliniche e ambulatori in tutta l'area per garantire la massima copertura</li>
            <li><strong>Tempi di intervento ridotti:</strong> La vicinanza geografica permette assistenza rapida, fondamentale nelle emergenze</li>
            <li><strong>Approccio personalizzato:</strong> Seguiamo ogni paziente nel tempo, costruendo un rapporto di fiducia duraturo</li>
        </ul>
    </div>";
}

function generateFullServiceLocationContent($serviceName, $serviceNameLower, $locationFull, $locationName, $locationIn, $locationPrep, $locationType)
{
    // This is a fallback when no base content exists - generates generic but localized content
    $locationLabel = $locationType === 'provincia' ? "della provincia di {$locationName}" : "di {$locationName}";

    return "
    <div class='full-generated-content'>
        <div style='background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 2rem; border-radius: 8px; margin-bottom: 2rem; border-left: 4px solid var(--accent-color);'>
            <h3 style='color: var(--primary-color); margin-top: 0;'>{$serviceName} {$locationPrep} {$locationFull}</h3>
            <p style='font-size: 1.1rem; margin-bottom: 0;'>Stai cercando servizi veterinari specializzati in <strong>{$serviceNameLower}</strong> {$locationIn}? La nostra rete di professionisti qualificati è presente sul territorio e pronta ad assisterti.</p>
        </div>
        
        <p>Il nostro servizio di <strong>{$serviceNameLower}</strong> {$locationIn} è fornito da veterinari altamente qualificati ed esperti nel settore. Comprendiamo quanto sia importante per te la salute e il benessere del tuo animale, per questo mettiamo a disposizione competenza professionale, tecnologie all'avanguardia e un approccio umano e personalizzato.</p>
        
        <h3 style='color: var(--primary-color);'>Perché scegliere i nostri professionisti {$locationLabel}?</h3>
        <p>I veterinari che collaborano con noi {$locationIn} non sono solo professionisti competenti, ma veri appassionati del proprio lavoro. Ogni intervento viene effettuato con la massima cura e attenzione, utilizzando protocolli aggiornati e attrezzature moderne che garantiscono diagnosi precise e trattamenti efficaci.</p>
        
        <p>La copertura territoriale capillare {$locationLabel} ci permette di offrire tempi di intervento rapidi, fondamentali soprattutto nelle situazioni di emergenza. La conoscenza approfondita del territorio locale consente inoltre ai nostri veterinari di comprendere meglio le specificità ambientali che possono influenzare la salute degli animali della zona.</p>
        
        <div style='background: #f0f9ff; padding: 1.5rem; border-radius: 6px; margin: 2rem 0; border-left: 3px solid var(--primary-color);'>
            <p style='margin: 0; font-weight: 600; color: var(--primary-color);'>📍 Servizio disponibile su tutto il territorio {$locationLabel}</p>
            <p style='margin: 0.5rem 0 0 0;'>Veterinari esperti, assistenza professionale, cura dedicata per ogni tipo di animale</p>
        </div>
        
        <h3 style='color: var(--primary-color);'>Cosa offriamo</h3>
        <ul style='line-height: 1.8; color: var(--text-muted);'>
            <li><strong>Consulenza specialistica:</strong> Ogni caso viene valutato individualmente con attenzione ai dettagli</li>
            <li><strong>Strutture moderne:</strong> Cliniche e ambulatori dotati delle migliori tecnologie diagnostiche</li>
            <li><strong>Disponibilità:</strong> Servizi programmati e assistenza per situazioni urgenti</li>
            <li><strong>Follow-up dedicato:</strong> Monitoraggio continuo del paziente e supporto post-trattamento</li>
        </ul>
        
        <p><strong>Contattaci ora</strong> per ricevere maggiori informazioni sul nostro servizio di {$serviceNameLower} {$locationIn} e prenota una consulenza con i migliori professionisti del settore veterinario.</p>
    </div>";
}
