<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

echo "<h1>Updating Services with SEO Content</h1>";

try {
    $pdo = db()->getConnection();

    // Define SEO content for each service
    $seoContent = [
        'veterinario-cani' => '<p>Il <strong>veterinario per cani</strong> è il professionista specializzato nella cura e nel benessere del tuo amico a quattro zampe. I cani necessitano di attenzioni mediche specifiche durante tutta la loro vita, dalla fase di cucciolo fino alla terza età. Un veterinario esperto in medicina canina è in grado di diagnosticare e trattare una vasta gamma di patologie, garantendo al tuo cane una vita lunga e in salute.</p>

<p>La medicina veterinaria canina abbraccia numerose specializzazioni: dalla chirurgia ortopedica per problemi articolari, alla dermatologia per allergie e patologie cutanee, fino alla cardiologia e oncologia. Un controllo veterinario regolare è fondamentale per la prevenzione: vaccinazioni, esami del sangue periodici e visite di routine permettono di individuare precocemente eventuali problematiche e intervenire tempestivamente.</p>

<p>Che tu abbia un cucciolo energico, un cane adulto attivo o un anziano fedele compagno, affidarsi a un veterinario competente significa garantire al tuo animale le migliori cure possibili. Dalla gestione delle emergenze al supporto nutrizionale personalizzato, il veterinario per cani è il punto di riferimento per ogni esigenza di salute del tuo pet.</p>

<p><strong>Richiedi una consulenza gratuita</strong> per trovare il veterinario specializzato in cani più vicino a te e assicura al tuo amico peloso le cure che merita.</p>',

        'veterinario-gatti' => '<p>Il <strong>veterinario per gatti</strong> è uno specialista nella medicina felina, un campo che richiede competenze specifiche data la natura particolare di questi animali. I gatti hanno esigenze mediche uniche e spesso manifestano i sintomi di malattie in modo diverso rispetto ad altri animali domestici, rendendo fondamentale l\'esperienza di un professionista dedicato.</p>

<p>La medicina felina copre numerose aree di intervento: dalla gestione delle patologie urinarie (molto comuni nei gatti), alle malattie dentali, fino alle problematiche comportamentali legate allo stress. Un veterinario esperto in gatti sa come approcciare questi animali sensibili, minimizzando l\'ansia durante le visite e garantendo diagnosi accurate attraverso esami specifici.</p>

<p>La prevenzione è particolarmente importante per i gatti: vaccinazioni contro malattie comuni, controlli periodici per patologie renali e tiroidee, e screening per malattie infettive come FIV e FeLV sono essenziali per mantenere il tuo gatto in salute. Anche la gestione nutrizionale gioca un ruolo cruciale, specialmente per gatti con predisposizioni a obesità o allergie alimentari.</p>

<p><strong>Contattaci per una consulenza gratuita</strong> e trova il veterinario specializzato in gatti ideale per le esigenze del tuo felino.</p>',

        'veterinario-esotici' => '<p>Il <strong>veterinario per animali esotici</strong> è un professionista altamente specializzato nella cura di pet non convenzionali come furetti, conigli, porcellini d\'India, cincillà e altri piccoli mammiferi. Questi animali richiedono competenze veterinarie specifiche, poiché le loro esigenze mediche differiscono notevolmente da quelle di cani e gatti.</p>

<p>La medicina degli animali esotici è un campo in continua evoluzione che comprende diagnostica avanzata, chirurgia delicata e terapie mirate. I conigli, ad esempio, necessitano di cure dentali specializzate per i loro denti a crescita continua, mentre i furetti richiedono attenzione particolare per problematiche endocrine. Ogni specie ha le proprie peculiarità anatomiche e fisiologiche che solo un veterinario esperto può gestire adeguatamente.</p>

<p>Un aspetto fondamentale della cura degli esotici è la prevenzione: alimentazione corretta, ambiente adatto e controlli regolari sono essenziali per evitare patologie comuni. Il veterinario specializzato è in grado di fornire consulenza nutrizionale specifica per ogni specie, consigli sulla gestione dell\'habitat e programmi vaccinali personalizzati dove necessario.</p>

<p><strong>Richiedi una consulenza gratuita</strong> per trovare il veterinario esperto in animali esotici più vicino a te e garantire al tuo piccolo amico le cure specialistiche di cui ha bisogno.</p>',

        'veterinario-rettili' => '<p>Il <strong>veterinario per rettili</strong> è uno specialista in erpetologia veterinaria, dedicato alla cura di tartarughe, serpenti, iguane, gechi e altri rettili domestici. Questi animali affascinanti richiedono conoscenze mediche molto specifiche, poiché la loro fisiologia è radicalmente diversa da quella dei mammiferi.</p>

<p>La medicina dei rettili presenta sfide uniche: dalla gestione della temperatura corporea alle esigenze nutrizionali specifiche per ogni specie, fino alle patologie metaboliche legate a carenze di calcio e vitamina D3. Un veterinario esperto in rettili sa come interpretare i sottili segnali di malattia che questi animali manifestano, spesso mascherando i sintomi fino a stadi avanzati.</p>

<p>Le problematiche più comuni includono malattie respiratorie, parassitosi, ritenzione di uova nelle femmine e patologie dermatologiche. Il veterinario specializzato utilizza tecniche diagnostiche adattate ai rettili, come radiografie specifiche e analisi del sangue interpretate secondo parametri erpetologici. Anche la chirurgia richiede competenze particolari data l\'anatomia unica di questi animali.</p>

<p><strong>Contattaci per una consulenza gratuita</strong> e trova il veterinario esperto in rettili che possa prendersi cura del tuo amico a sangue freddo con la professionalità che merita.</p>',

        'veterinario-uccelli' => '<p>Il <strong>veterinario per uccelli</strong> o ornitologo veterinario è uno specialista dedicato alla salute e al benessere degli uccelli domestici, dai piccoli canarini e pappagallini fino ai grandi pappagalli e rapaci. L\'ornitologia veterinaria è una disciplina complessa che richiede anni di studio e esperienza specifica.</p>

<p>Gli uccelli sono animali particolarmente delicati che nascondono i segni di malattia per istinto di sopravvivenza, rendendo cruciale l\'occhio esperto di un veterinario specializzato. Le patologie più comuni includono malattie respiratorie, problematiche del becco e delle penne, infezioni batteriche e virali, oltre a disturbi comportamentali legati allo stress.</p>

<p>Un veterinario aviare offre servizi completi: dalla diagnosi precoce attraverso esami clinici e di laboratorio specifici, alla chirurgia delicata quando necessaria, fino alla gestione nutrizionale personalizzata. La prevenzione è fondamentale: controlli periodici, sessaggio DNA, esami delle feci per parassiti e consulenza sulla gestione ambientale sono essenziali per mantenere i vostri volatili in salute ottimale.</p>

<p><strong>Richiedi una consulenza gratuita</strong> per trovare il veterinario specializzato in uccelli più qualificato nella tua zona e assicura al tuo volatile le cure specialistiche che merita.</p>',

        'veterinario-cavalli' => '<p>Il <strong>veterinario equino</strong> è uno specialista nella medicina e chirurgia dei cavalli, un campo che richiede competenze altamente specifiche data la complessità anatomica e le esigenze particolari di questi magnifici animali. Che tu possieda un cavallo sportivo, da lavoro o da compagnia, un veterinario equino esperto è essenziale per garantirne salute e performance ottimali.</p>

<p>La medicina equina abbraccia numerose specializzazioni: dalla medicina sportiva per cavalli da competizione, alla chirurgia ortopedica per problematiche locomotorie, fino alla riproduzione equina e neonatologia. Il veterinario equino gestisce emergenze come coliche, laminiti e traumi, fornendo interventi tempestivi che possono salvare la vita dell\'animale.</p>

<p>Un aspetto cruciale è la medicina preventiva: vaccinazioni programmate, sverminazioni regolari, cure dentali (odontostomatologia equina) e controlli podologici sono fondamentali. Il veterinario equino lavora spesso in sinergia con maniscalchi, fisioterapisti e nutrizionisti per garantire un approccio olistico al benessere del cavallo.</p>

<p><strong>Contattaci per una consulenza gratuita</strong> e trova il veterinario equino più qualificato per prendersi cura del tuo cavallo con la professionalità e l\'esperienza che questo animale nobile richiede.</p>',

        'veterinario-fattoria' => '<p>Il <strong>veterinario per animali da fattoria</strong> è uno specialista nella medicina degli animali da reddito, dedicato alla salute e alla produttività di bovini, ovini, suini, caprini e altri animali da allevamento. Questo professionista svolge un ruolo cruciale nella filiera agroalimentare, garantendo il benessere animale e la sicurezza dei prodotti destinati al consumo umano.</p>

<p>La medicina veterinaria rurale comprende la gestione sanitaria di intere mandrie e greggi, dalla prevenzione delle malattie infettive alla medicina di popolazione. Il veterinario per animali da fattoria si occupa di programmi vaccinali, controlli riproduttivi, gestione nutrizionale ottimale e biosicurezza aziendale. Interviene inoltre nelle emergenze sanitarie e nelle patologie individuali che richiedono trattamenti specifici.</p>

<p>Un aspetto fondamentale è la consulenza aziendale: il veterinario collabora con gli allevatori per ottimizzare le condizioni di allevamento, migliorare le performance produttive e garantire la conformità alle normative sanitarie. La tracciabilità alimentare e il benessere animale sono priorità assolute nella moderna zootecnia.</p>

<p><strong>Richiedi una consulenza gratuita</strong> per trovare il veterinario specializzato in animali da fattoria più esperto nella tua zona e garantire la salute del tuo allevamento.</p>',

        'veterinario-pesci' => '<p>Il <strong>veterinario per pesci</strong> è uno specialista in medicina ittica, dedicato alla salute degli animali acquatici d\'ornamento e da acquariofilia. Questo campo emergente della veterinaria richiede conoscenze approfondite di fisiologia acquatica, chimica dell\'acqua e patologie specifiche dei pesci.</p>

<p>I pesci d\'acquario, sia d\'acqua dolce che marina, possono sviluppare numerose patologie: infezioni batteriche, parassitosi, micosi e malattie virali. Il veterinario esperto in ittiologia sa diagnosticare questi problemi attraverso l\'osservazione clinica, analisi dell\'acqua e, quando necessario, esami di laboratorio specifici. La qualità dell\'acqua è fondamentale: parametri come pH, ammoniaca, nitriti e salinità devono essere costantemente monitorati.</p>

<p>La prevenzione è essenziale in acquariofilia: quarantena dei nuovi pesci, alimentazione equilibrata, gestione ottimale del filtro biologico e manutenzione regolare dell\'acquario prevengono la maggior parte delle problematiche. Il veterinario specializzato fornisce consulenza personalizzata sulla gestione dell\'ecosistema acquatico e interviene tempestivamente in caso di epidemie.</p>

<p><strong>Contattaci per una consulenza gratuita</strong> e trova il veterinario esperto in pesci che possa aiutarti a mantenere il tuo acquario in perfetta salute.</p>',

        'veterinario-piccoli-mammiferi' => '<p>Il <strong>veterinario per piccoli mammiferi</strong> è uno specialista dedicato alla cura di roditori domestici come criceti, cavie, ratti, topi e gerbilli. Questi piccoli animali, sempre più diffusi come pet, richiedono competenze veterinarie specifiche data la loro delicata fisiologia e le patologie caratteristiche.</p>

<p>I piccoli mammiferi presentano sfide mediche uniche: crescita dentale continua (che può causare malocclusioni), tendenza a nascondere i sintomi di malattia e metabolismo rapidissimo che richiede interventi tempestivi. Il veterinario specializzato sa riconoscere i sottili segnali di malessere e intervenire con terapie adeguate, spesso adattando farmaci dosati per animali più grandi.</p>

<p>La prevenzione è fondamentale: alimentazione corretta specifica per ogni specie, ambiente arricchito con stimoli appropriati e controlli veterinari regolari sono essenziali. Le patologie più comuni includono problemi respiratori, tumori (specialmente in ratti anziani), parassitosi e disturbi gastrointestinali. La chirurgia su questi piccoli pazienti richiede strumentazione microscopica e grande esperienza.</p>

<p><strong>Richiedi una consulenza gratuita</strong> per trovare il veterinario specializzato in piccoli mammiferi più qualificato e garantire al tuo piccolo amico le cure che merita.</p>',

        'veterinario-anfibi' => '<p>Il <strong>veterinario per anfibi</strong> è uno specialista in medicina veterinaria degli anfibi, dedicato alla cura di rane, rospi, tritoni, salamandre e altri anfibi domestici. Questo campo di nicchia richiede conoscenze approfondite della fisiologia unica di questi animali che vivono tra ambiente acquatico e terrestre.</p>

<p>Gli anfibi presentano caratteristiche fisiologiche particolari: respirazione cutanea, metamorfosi complessa e sensibilità estrema alle condizioni ambientali. Il veterinario specializzato sa gestire problematiche come malattie fungine della pelle, infezioni batteriche, parassitosi e disturbi metabolici legati a temperature e umidità non ottimali.</p>

<p>La prevenzione passa attraverso la corretta gestione del terrario o acquaterrario: qualità dell\'acqua, temperatura controllata, substrato appropriato e alimentazione viva o surgelata di qualità. Il veterinario per anfibi fornisce consulenza dettagliata sulla riproduzione, sulla stagionalità e sulle esigenze specifiche di ogni specie, molte delle quali protette e soggette a normative CITES.</p>

<p><strong>Contattaci per una consulenza gratuita</strong> e trova il veterinario esperto in anfibi che possa seguire il tuo pet acquatico con la competenza necessaria.</p>',

        'chirurgia-veterinaria' => '<p>La <strong>chirurgia veterinaria</strong> è una specializzazione medica fondamentale che abbraccia interventi di routine come sterilizzazioni e castrazioni, fino a operazioni complesse di chirurgia ortopedica, oncologica e dei tessuti molli. Un chirurgo veterinario esperto utilizza tecniche all\'avanguardia per garantire risultati ottimali e recuperi rapidi.</p>

<p>La chirurgia ortopedica veterinaria gestisce fratture, lussazioni, rotture legamentose (come la rottura del crociato nei cani) e displasie articolari. Grazie a tecniche moderne come l\'osteosintesi con placche e viti, la maggior parte degli animali recupera completamente la funzionalità. La chirurgia dei tessuti molli include interventi addominali, toracici e su organi interni, richiedendo precisione millimetrica.</p>

<p>Fondamentale è l\'anestesiologia veterinaria: protocolli anestesiologici personalizzati, monitoraggio costante dei parametri vitali e gestione del dolore post-operatorio garantiscono la sicurezza del paziente. Le strutture chirurgiche moderne dispongono di sale operatorie sterili, strumentazione avanzata e reparti di terapia intensiva per il decorso post-operatorio.</p>

<p><strong>Richiedi una consulenza gratuita</strong> per interventi chirurgici veterinari e affidati a professionisti qualificati con esperienza e tecnologie all\'avanguardia.</p>',

        'pronto-soccorso' => '<p>Il <strong>pronto soccorso veterinario H24</strong> è un servizio essenziale per gestire emergenze mediche che non possono attendere. Traumi, avvelenamenti, difficoltà respiratorie, convulsioni, coliche e altre situazioni critiche richiedono intervento immediato da parte di veterinari esperti in emergenza.</p>

<p>Il pronto soccorso veterinario dispone di attrezzature diagnostiche avanzate: ecografi, radiologia digitale, laboratorio interno per analisi urgenti ed emogasanalisi. Il team di emergenza è addestrato a stabilizzare rapidamente il paziente, gestire lo shock, somministrare ossigenoterapia e intervenire chirurgicamente quando necessario, anche nelle ore notturne e nei festivi.</p>

<p>Situazioni comuni che richiedono pronto soccorso includono: dilatazione-torsione gastrica nei cani di grossa taglia, traumi da investimento, crisi epilettiche, blocchi urinari nei gatti maschi, parto distocico e reazioni allergiche acute. La tempestività dell\'intervento può fare la differenza tra la vita e la morte del vostro animale.</p>

<p><strong>Contattaci immediatamente</strong> in caso di emergenza veterinaria. Il servizio di pronto soccorso H24 è attivo tutti i giorni, 24 ore su 24, per garantire assistenza quando il tuo pet ne ha più bisogno.</p>',

        'vaccinazioni' => '<p>Le <strong>vaccinazioni veterinarie</strong> sono lo strumento preventivo più efficace per proteggere il tuo animale da malattie infettive potenzialmente letali. Un programma vaccinale corretto, personalizzato in base a specie, età, stile di vita e area geografica, è fondamentale per garantire una protezione ottimale.</p>

<p>Per i cani, le vaccinazioni core includono cimurro, parvovirosi, epatite infettiva e leptospirosi. Per i gatti, panleucopenia, rinotracheite e calicivirosi sono essenziali. Il <strong>microchip</strong> è obbligatorio per legge e permette l\'identificazione univoca dell\'animale, fondamentale in caso di smarrimento e per viaggi all\'estero.</p>

<p>Il protocollo vaccinale inizia in giovane età con vaccinazioni primarie seguite da richiami annuali o triennali secondo le linee guida internazionali. Il veterinario valuta lo stato di salute prima di ogni vaccinazione, garantendo che l\'animale sia in condizioni ottimali per sviluppare un\'adeguata risposta immunitaria. Insieme al microchip, le vaccinazioni rappresentano la base della medicina preventiva.</p>

<p><strong>Prenota una consulenza</strong> per il piano vaccinale del tuo pet e l\'applicazione o verifica del microchip di identificazione.</p>',

        'visite-domicilio' => '<p>Le <strong>visite veterinarie a domicilio</strong> rappresentano una soluzione innovativa e confortevole per garantire cure mediche al tuo animale nel suo ambiente familiare. Questo servizio è particolarmente indicato per animali anziani, ansiosi, con difficoltà di trasporto o per proprietari con mobilità ridotta.</p>

<p>Il veterinario a domicilio porta con sé attrezzature diagnostiche portatili che permettono di effettuare visite complete: auscultazione, palpazione, misurazione dei parametri vitali, prelievi ematici e somministrazione di farmaci o vaccini. L\'ambiente domestico riduce notevolmente lo stress dell\'animale, permettendo una valutazione comportamentale più accurata e facilitando la compliance terapeutica.</p>

<p>Le visite domiciliari sono ideali per: controlli geriatrici regolari, gestione di patologie croniche, cure palliative, medicazioni post-operatorie, vaccinazioni e microchip. Anche nuclei con più animali beneficiano di questo servizio, evitando trasferte multiple in clinica. Tuttavia, per interventi chirurgici o diagnostica avanzata, la struttura veterinaria rimane necessaria.</p>

<p><strong>Richiedi il servizio di visite a domicilio</strong> e offri al tuo animale cure veterinarie professionali nel comfort della propria casa.</p>',

        'dermatologia-veterinaria' => '<p>La <strong>dermatologia veterinaria</strong> è una specializzazione medica dedicata alle patologie cutanee degli animali, un campo in continua espansione data l\'elevata frequenza di problemi dermatologici. Prurito cronico, perdita di pelo, lesioni cutanee e otiti ricorrenti sono segnali che richiedono l\'intervento di un dermatologo veterinario.</p>

<p>Le cause di patologie dermatologiche sono molteplici: allergie alimentari o ambientali (atopia), parassitosi (pulci, acari, rogna), infezioni batteriche o fungine (malassezia, dermatofiti), e malattie autoimmuni. Il dermatologo veterinario utilizza strumenti diagnostici specifici: raschiati cutanei, citologie, colture batteriche e fungine, test allergologici e biopsie quando necessario.</p>

<p>Il trattamento può includere terapie topiche, farmaci sistemici, immunoterapia allergica e gestione nutrizionale. Le dermatiti allergiche, in particolare, richiedono approcci personalizzati a lungo termine. La dermatologia veterinaria moderna offre soluzioni innovative come anticorpi monoclonali per gestire il prurito cronico senza effetti collaterali significativi.</p>

<p><strong>Prenota una consulenza dermatologica</strong> se il tuo animale soffre di problemi cutanei persistenti e trova sollievo con l\'aiuto di uno specialista.</p>',

        'dentista-veterinario' => '<p>L\'<strong>odontostomatologia veterinaria</strong> è la branca specializzata nella salute orale degli animali, un aspetto spesso sottovalutato ma cruciale per il benessere generale. La malattia parodontale colpisce oltre l\'80% dei cani e gatti sopra i 3 anni, causando dolore, infezioni e potenziali problemi sistemici.</p>

<p>Il dentista veterinario esegue detartrasi professionali in anestesia generale, permettendo pulizia sottogengivale completa, valutazione radiografica dei denti e trattamenti specifici. Le procedure includono estrazioni dentarie quando necessario, cure di carie, trattamenti endodontici (devitalizzazioni) e chirurgia orale per tumori o malformazioni.</p>

<p>La prevenzione dentale domestica è fondamentale: spazzolamento regolare, dieta appropriata e prodotti masticabili specifici aiutano a mantenere denti e gengive sani. I controlli periodici permettono di individuare precocemente problemi come gengivite, esposizione della radice, fratture dentali o masse orali. Un sorriso sano è sinonimo di un pet sano.</p>

<p><strong>Richiedi una visita odontoiatrica</strong> per valutare la salute orale del tuo animale e prevenire problemi futuri con cure professionali.</p>',

        'oculista-veterinario' => '<p>L\'<strong>oftalmologia veterinaria</strong> è la specializzazione dedicata alla salute degli occhi degli animali, organi delicati e fondamentali per la qualità di vita. Arrossamenti oculari, secrezioni, opacità corneali, strabismo o cambiamenti nel comportamento visivo richiedono valutazione oftalmologica immediata.</p>

<p>L\'oculista veterinario utilizza strumentazione specifica: oftalmoscopio, tonometro per misurare la pressione oculare, test di Schirmer per la produzione lacrimale e lampada a fessura per esami dettagliati. Le patologie comuni includono congiuntiviti, ulcere corneali, cataratta, glaucoma, uveiti e distacco di retina.</p>

<p>Molte razze hanno predisposizioni genetiche a problemi oculari: entropion, ectropion, atrofia progressiva della retina, lussazione del cristallino. La diagnosi precoce permette trattamenti conservativi o chirurgici tempestivi, preservando la vista. L\'oftalmologia veterinaria offre anche interventi chirurgici avanzati come la facoemulsificazione per cataratta o chirurgia del glaucoma.</p>

<p><strong>Prenota una visita oftalmologica</strong> se noti alterazioni negli occhi del tuo animale. La vista è preziosa, affidati a uno specialista.</p>',

        'cardiologo-veterinario' => '<p>La <strong>cardiologia veterinaria</strong> è una specializzazione medica fondamentale per la diagnosi e gestione delle cardiopatie, patologie sempre più frequenti negli animali domestici. Tosse, affaticamento, ridotta tolleranza all\'esercizio e svenimenti possono indicare problemi cardiaci che richiedono valutazione cardiologica.</p>

<p>Il cardiologo veterinario esegue esami diagnostici avanzati: <strong>ecocardiografia Doppler</strong> per valutare anatomia e funzionalità cardiaca, elettrocardiogramma (ECG) per rilevare aritmie, misurazione della pressione arteriosa e radiografie toraciche. Questi strumenti permettono diagnosi precise di insufficienza valvolare, cardiomiopatie dilatative o ipertrofiche, difetti congeniti e versamenti pericardici.</p>

<p>Molte cardiopatie sono gestibili con terapie farmacologiche appropriate che migliorano significativamente qualità e durata della vita. Il monitoraggio cardiologico regolare permette di adeguare le terapie e cogliere tempestivamente eventuali peggioramenti. Alcune patologie beneficiano di interventi specialistici come il posizionamento di pacemaker.</p>

<p><strong>Richiedi una visita cardiologica</strong> se il tuo veterinario sospetta problemi cardiaci o per screening di razze predisposte.</p>',

        'oncologo-veterinario' => '<p>L\'<strong>oncologia veterinaria</strong> è la branca specializzata nella diagnosi e trattamento dei tumori negli animali. Grazie ai progressi della medicina veterinaria, molte neoplasie sono oggi curabili o gestibili, offrendo al pet un\'eccellente qualità di vita anche dopo la diagnosi oncologica.</p>

<p>L\'oncologo veterinario elabora piani terapeutici personalizzati che possono includere: chirurgia oncologica per rimozione completa del tumore, <strong>chemioterapia</strong> con protocolli adattati dalla medicina umana ma con minori effetti collaterali, radioterapia e immunoterapia. La diagnosi precoce attraverso esami citologici, istologici e stadiazione completa (radiografie, ecografie, TC) è cruciale per la prognosi.</p>

<p>I tumori più comuni negli animali includono: linfomi, mastocitomi, melanomi, osteosarcomi e carcinomi mammari. L\'approccio multidisciplinare coinvolge chirurghi, oncologi medici e specialisti in cure palliative per garantire il miglior outcome possibile. Il supporto al proprietario durante il percorso terapeutico è parte integrante dell\'oncologia veterinaria.</p>

<p><strong>Richiedi una consulenza oncologica</strong> se è stata rilevata una massa sospetta nel tuo animale. La diagnosi precoce salva vite.</p>',

        'ecografia-radiologia' => '<p>La <strong>diagnostica per immagini veterinaria</strong> comprende tecniche fondamentali per diagnosi accurate: radiologia digitale, ecografia, tomografia computerizzata (TC) e risonanza magnetica (RM). Questi strumenti permettono di visualizzare internamente l\'animale senza procedure invasive, guidando diagnosi e terapie.</p>

<p>La <strong>radiologia veterinaria</strong> è essenziale per valutare scheletro, torace e addome, diagnosticando fratture, displasie, corpi estranei, patologie polmonari e cardiache. L\'<strong>ecografia</strong> eccelle nell\'imaging dei tessuti molli: organi addominali, cuore (ecocardiografia), utero gravido, masse e raccolte fluide. Entrambe sono tecniche non invasive e generalmente non richiedono sedazione.</p>

<p>La diagnostica avanzata con TC e RM fornisce immagini tridimensionali dettagliate, indispensabili per pianificazione chirurgica complessa, valutazione neurologica e staディazione oncologica. Veterinari specializzati in diagnostica per immagini interpretano questi esami, fornendo referti dettagliati che guidano i colleghi clinici nella gestione terapeutica ottimale.</p>

<p><strong>Prenota esami diagnostici avanzati</strong> per diagnosi precise e piani terapeutici mirati. La diagnostica per immagini è la finestra sulla salute del tuo pet.</p>'
    ];

    $updateCount = 0;
    $errorCount = 0;

    foreach ($seoContent as $slug => $content) {
        try {
            $stmt = $pdo->prepare("UPDATE servizi SET contenuto = :content WHERE slug = :slug");
            $result = $stmt->execute([
                ':content' => $content,
                ':slug' => $slug
            ]);

            if ($result) {
                $updateCount++;
                echo "<p style='color: green;'>✓ Updated: <strong>$slug</strong></p>";
            } else {
                $errorCount++;
                echo "<p style='color: orange;'>⚠ No rows updated for: <strong>$slug</strong> (service might not exist)</p>";
            }
        } catch (Exception $e) {
            $errorCount++;
            echo "<p style='color: red;'>✗ Error updating $slug: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    echo "<hr>";
    echo "<h2 style='color: " . ($errorCount > 0 ? "orange" : "green") . ";'>Summary</h2>";
    echo "<p><strong>Successfully updated:</strong> $updateCount services</p>";
    echo "<p><strong>Errors:</strong> $errorCount</p>";

    if ($updateCount > 0) {
        echo "<p style='color: green; font-weight: bold;'>✓ SEO content has been successfully added to service pages!</p>";
        echo "<p>You can now visit service pages like <code>/servizi/veterinario-cani</code> to see the new SEO content.</p>";
    }

} catch (Exception $e) {
    echo "<h3 style='color:red'>Error: " . htmlspecialchars($e->getMessage()) . "</h3>";
}
?>