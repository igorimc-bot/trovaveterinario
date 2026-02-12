<?php
/**
 * Text Generator
 * Generates dynamic, SEO-friendly content for Service + Location pages
 */

function generateServiceLocationContent($service, $location, $locationType)
{
    // Basic data preparation
    $serviceName = $service['nome'];
    $serviceNameLower = strtolower($serviceName);
    $locationName = $location['nome'];
    $locationPrep = ($locationType === 'comune') ? 'a' : 'in';
    $locationFull = (($locationType === 'provincia') ? 'Provincia di ' : '') . $locationName;

    // Prepare variables for templates
    $vars = [
        '{service}' => $serviceName,
        '{service_lower}' => $serviceNameLower,
        '{location}' => $locationFull,
        '{prep}' => $locationPrep,
        '{location_name}' => $locationName
    ];

    // Seed the random generator with a hash of the current page's unique identifiers
    // This ensures the text is "random" but CONSISTENT for the same page (SEO friendly, doesn't change on every refresh)
    $seedString = $service['slug'] . $location['slug'] . $locationType;
    srand(crc32($seedString));

    // --- SECTION 1: INTRODUCTION ---
    $introTemplates = [
        "Stai cercando un'opportunità per <strong>{service_lower} {prep} {location}</strong>? Sei nel posto giusto. Il mercato delle aste giudiziarie offre occasioni uniche per risparmiare notevolmente rispetto al mercato tradizionale, ma muoversi in questo settore richiede competenza e precisione.",
        "Acquistare <strong>{service_lower} {prep} {location}</strong> tramite asta giudiziaria può essere un investimento straordinario. Tuttavia, la burocrazia e le procedure legali possono sembrare complesse per chi non è del settore. Ecco perché è fondamentale affidarsi a professionisti esperti.",
        "Hai individuato un'asta per <strong>{service_lower} {prep} {location}</strong> e vorresti partecipare? Le aste rappresentano una via d'accesso privilegiata a beni di valore a prezzi competitivi, spesso con risparmi fino al 50% sul valore di mercato.",
        "Il settore delle aste immobiliari e mobiliari è in forte crescita. Se il tuo obiettivo è acquistare <strong>{service_lower} {prep} {location}</strong>, la nostra piattaforma ti offre tutti gli strumenti necessari per partecipare in sicurezza e con consapevolezza.",
    ];

    // --- SECTION 2: PROBLEM / PAIN POINTS ---
    $problemTemplates = [
        "Molti privati rinunciano a partecipare alle aste per paura delle insidie burocratiche o per la difficoltà nel reperire informazioni corrette. Errori nella procedura o nella valutazione del bene possono costare caro.",
        "Senza una guida esperta, è facile perdersi tra perizie tecniche, avvisi di vendita e scadenze inderogabili. Il rischio è quello di vedere sfumare un affare o, peggio, di incorrere in problemi legali post-aggiudicazione.",
        "La partecipazione 'fai-da-te' nasconde diverse criticità: dalla lettura corretta della perizia alla verifica di eventuali abusi edilizi o gravami che non vengono cancellati dal decreto di trasferimento.",
        "Spesso le informazioni disponibili online sono frammentate o poco chiare. Capire realmente lo stato di fatto di un bene e le sue potenzialità richiede un occhio clinico che solo anni di esperienza possono garantire.",
    ];

    // --- SECTION 3: OUR SOLUTION & LOCAL EXPERTISE ---
    $solutionTemplates = [
        "<strong>Aste Giudiziarie 24</strong> mette a tua disposizione un team di esperti radicati nel territorio di <strong>{location}</strong>. Conosciamo a fondo le dinamiche del tribunale locale e le specificità del mercato immobiliare della zona.",
        "Il nostro servizio di assistenza è pensato per accompagnarti in ogni fase: dalla selezione dei migliori <strong>{service_lower} {prep} {location}</strong>, allo studio della documentazione, fino alla partecipazione all'asta (in presenza o telematica).",
        "Grazie alla nostra rete di professionisti attivi su <strong>{location}</strong>, offriamo una consulenza a 360 gradi. Non siamo semplici intermediari, ma partner tecnici che tutelano i tuoi interessi prima, durante e dopo l'acquisto.",
        "Con noi, partecipare a un'asta a <strong>{location}</strong> diventa semplice e sicuro. I nostri consulenti analizzano per te ogni dettaglio, segnalandoti tempestivamente eventuali rischi e stimando con precisione i costi occulti.",
    ];

    // --- SECTION 4: HOW IT WORKS / BENEFITS ---
    $processTemplates = [
        "Il nostro metodo è trasparente: prima di tutto verifichiamo la fattibilità dell'operazione. Se decidi di procedere, prepariamo la domanda di partecipazione e ti assistiamo durante la gara. Il nostro compenso matura solo in caso di successo o è chiaramente preventivato per le fasi di consulenza.",
        "Ti offriamo un vantaggio competitivo fondamentale: la preparazione. Arrivare al giorno dell'asta sapendo esattamente quanto rilanciare e conoscendo ogni aspetto del bene ti permette di fare affari migliori e senza sorprese.",
        "Non dovrai preoccuparti di nulla. Ci occupiamo noi di prenotare la visita, dialogare con il custode giudiziario e gestire tutte le pratiche telematiche. Tu potrai concentrarti solo sull'obiettivo: aggiudicarti il bene ed espandere il tuo patrimonio.",
        "Oltre all'assistenza tecnica e legale, possiamo supportarti anche nell'ottenimento di mutui o finanziamenti specifici per l'acquisto in asta, grazie a convenzioni con i principali istituti di credito operanti su <strong>{location}</strong>.",
    ];

    // --- SECTION 5: CALL TO ACTION ---
    $ctaTemplates = [
        "Non perdere l'occasione della vita. Contattaci oggi stesso per una prima consulenza gratuita su <strong>{service_lower} {prep} {location}</strong> compila il form qui sotto.",
        "Vuoi saperne di più? I nostri consulenti sono pronti a rispondere a tutte le tue domande. Richiedi subito informazioni senza impegno per le aste a <strong>{location}</strong>.",
        "Il tempo è un fattore chiave nelle aste. Se hai visto un bene che ti interessa a <strong>{location}</strong>, scrivici subito. Valuteremo insieme come procedere per assicurarci la vittoria.",
        "Inizia oggi il tuo percorso verso un acquisto sicuro e vantaggioso. Compila il modulo per essere ricontattato da un esperto della zona di <strong>{location}</strong>.",
    ];

    // Select templates using the seeded random function
    $selectedIntro = str_replace(array_keys($vars), array_values($vars), $introTemplates[rand(0, count($introTemplates) - 1)]);
    $selectedProblem = str_replace(array_keys($vars), array_values($vars), $problemTemplates[rand(0, count($problemTemplates) - 1)]);
    $selectedSolution = str_replace(array_keys($vars), array_values($vars), $solutionTemplates[rand(0, count($solutionTemplates) - 1)]);
    $selectedProcess = str_replace(array_keys($vars), array_values($vars), $processTemplates[rand(0, count($processTemplates) - 1)]);
    $selectedCta = str_replace(array_keys($vars), array_values($vars), $ctaTemplates[rand(0, count($ctaTemplates) - 1)]);

    // Assemble HTML
    $html = "
    <div class='generated-content'>
        <p class='lead'>$selectedIntro</p>
        <p>$selectedProblem</p>
        <h3>Perché affidarsi ai nostri esperti a {location_name}</h3>
        <p>$selectedSolution</p>
        <p>$selectedProcess</p>
        <div class='callout-box' style='background: #f8fafc; padding: 1.5rem; border-left: 4px solid var(--accent-color); margin: 2rem 0; border-radius: 4px;'>
            <strong>Punto chiave:</strong> $selectedCta
        </div>
    </div>
    ";

    // Final replacement of placeholders in the assembled HTML (just in case)
    return str_replace(array_keys($vars), array_values($vars), $html);
}
