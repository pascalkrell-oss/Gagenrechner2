# Phase 6 – QA-, Debugging- und Datenfluss-Notizen

## Hauptfälle

- Werbung mit Bild
- Werbung ohne Bild
- Webvideo / Imagefilm / Präsentation unpaid (1.3)
- App
- Telefonansage
- E-Learning / Audioguide
- Podcast
- Hörbuch
- Games
- Redaktionell / Doku / TV-Reportage
- Audiodeskription
- Kleinräumige Nutzung
- Session Fee

## Redirect-Logik

- Einstiegsszenarien werden zunächst als `selected_case` übernommen.
- Der Resolver normalisiert alle Eingaben und schreibt jeden Schritt in `route_trace`.
- Redirect-Regeln greifen nur für passende `source_case`-Einträge.
- Nicht ausgelöste Regeln bleiben als `suppressed_invalid_path` im Trace sichtbar.
- Danach greifen Business-Guards, damit fachlich unzulässige Fälle nicht versehentlich in falschen Hauptfällen bleiben.

## Sonderlogiken

- Zusatzjahr, Zusatzterritorium und Zusatzmotiv rechnen als 100-%-Addons auf den Basisausgangswert.
- Reminder, Allongen und Archivgage werden als prozentuale Zusatzpfade geführt.
- Mindestgage-Fälle rechnen zuerst minutenbasiert und ergänzen danach nur den notwendigen Ausgleich.
- Nachgage / Folgeauswertung läuft als separate Anrechnung über `prior_layout_fee`.
- `manual_offer_total` bleibt immer getrennt von der Empfehlung und wird nie in Basispositionen hineingeschrieben.
- Unbegrenzte Nutzung arbeitet als Multiplikator auf den fachlich errechneten Zwischenstand vor Layout-Anrechnung.

## Datenfluss

`Input -> UI State Sanitizing -> Resolver -> Calculator -> Result Formatter -> Frontend -> Export Payload -> Offer/PDF Document`

### Relevante Stationen

- `SGK_UI_State::sanitize_input()` bereinigt boolesche, ganzzahlige und dezimale Eingaben.
- `SGK_Resolver::resolve()` erzeugt `normalized_input`, `route_trace` und `resolver_meta`.
- `SGK_Calculator::calculate()` erzeugt totals, line_items, credits, alternatives und das erste `export_payload`.
- `SGK_Result_Formatter::format()` baut daraus:
  - `rights_overview`
  - `offer_positions`
  - `offer_notes`
  - `route_summary_offer`
  - `document_payload`
- Im Frontend wird das Payload nochmals mit Projekt- und Angebotsmetadaten angereichert.

## Export / PDF

- Das fachliche Export-Payload entsteht zuerst im Calculator.
- Der Formatter erweitert dieses Payload um offer-/pdf-fähige Strukturen.
- Das Frontend ergänzt nur Präsentations- und Angebotsmetadaten, nicht die fachliche Rechenlogik.
- `manual_offer_total` bleibt im Export gesondert erhalten und wird zusätzlich als separate Angebotsposition ausgegeben.

## Phase-6-QA-Fokus

- lower / mid / upper werden vor dem Export auf konsistente Reihenfolge abgesichert.
- Rechteübersichten lesen Defaults robust aus Fallkonfigurationen aus.
- Route-Trace bleibt für Redirects, unterdrückte Pfade und Guards nachvollziehbar.
- localStorage-/Export-/Preview-Fluss darf keine Schattenquelle für `manual_offer_total` erzeugen.
