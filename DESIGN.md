---
version: alpha
name: The Con — LCARS Console
description: A personal command-console dashboard (weather, calendar, to-do, sticky notes, services) built in the visual language of Michael Okuda's LCARS displays for Star Trek — black viewport, colored structural panels, one signal-cyan control accent, entirely within a blue palette.

colors:
  primary: "#1034B1"
  primary-strong: "#0E0967"
  secondary-subtle: "#B1D9FF"
  secondary: "#67ABFF"
  secondary-strong: "#0056C5"
  tertiary: "#22D3EE"
  tertiary-strong: "#0891B2"
  tertiary-subtle: "#A5F3FC"
  neutral: "#52526A"
  surface: "#020207"
  surface-raised: "#060813"
  surface-overlay: "#101321"
  on-surface: "#E8EBF2"
  border: "#2B2C3D"
  error: "#A50E19"
  ink: "#000000"

typography:
  display:
    fontFamily: Michroma
    fontSize: 56px
    fontWeight: 400
    lineHeight: 1.0
    letterSpacing: -0.01em
  headline-lg:
    fontFamily: Michroma
    fontSize: 32px
    fontWeight: 400
    lineHeight: 1.05
    letterSpacing: 0em
  headline-md:
    fontFamily: Michroma
    fontSize: 22px
    fontWeight: 400
    lineHeight: 1.1
    letterSpacing: 0.01em
  headline-sm:
    fontFamily: IBM Plex Sans
    fontSize: 18px
    fontWeight: 600
    lineHeight: 1.25
    letterSpacing: 0em
  body-lg:
    fontFamily: IBM Plex Sans
    fontSize: 18px
    fontWeight: 400
    lineHeight: 1.6
  body-md:
    fontFamily: IBM Plex Sans
    fontSize: 16px
    fontWeight: 400
    lineHeight: 1.6
  body-sm:
    fontFamily: IBM Plex Sans
    fontSize: 14px
    fontWeight: 400
    lineHeight: 1.5
  label-caps:
    fontFamily: Michroma
    fontSize: 13px
    fontWeight: 400
    lineHeight: 1.3
    letterSpacing: 0.04em
  label-sm:
    fontFamily: IBM Plex Sans
    fontSize: 11px
    fontWeight: 500
    lineHeight: 1.3
    letterSpacing: 0.08em
  caption:
    fontFamily: IBM Plex Sans
    fontSize: 12px
    fontWeight: 400
    lineHeight: 1.4
  data-md:
    fontFamily: IBM Plex Mono
    fontSize: 15px
    fontWeight: 400
    lineHeight: 1.4
    fontFeature: "'tnum' 1"
  data-sm:
    fontFamily: IBM Plex Mono
    fontSize: 12px
    fontWeight: 400
    lineHeight: 1.35
    fontFeature: "'tnum' 1"

rounded:
  none: 0px
  end: 12px
  elbow: 24px
  full: 9999px

spacing:
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  2xl: 48px

components:
  page:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.on-surface}"
    typography: "{typography.body-md}"
  bar-title:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.on-surface}"
    typography: "{typography.display}"
    rounded: "{rounded.none}"
    padding: "{spacing.sm}"
  panel-structural:
    backgroundColor: "{colors.primary-strong}"
    textColor: "{colors.on-surface}"
    typography: "{typography.label-caps}"
    rounded: "{rounded.none}"
  panel-accent:
    backgroundColor: "{colors.secondary-strong}"
    textColor: "{colors.on-surface}"
    typography: "{typography.label-caps}"
    rounded: "{rounded.none}"
  panel-decorative:
    backgroundColor: "{colors.secondary}"
    textColor: "{colors.ink}"
    typography: "{typography.label-caps}"
    rounded: "{rounded.end}"
  panel-decorative-subtle:
    backgroundColor: "{colors.secondary-subtle}"
    textColor: "{colors.ink}"
    rounded: "{rounded.elbow}"
  button-primary:
    backgroundColor: "{colors.tertiary}"
    textColor: "{colors.ink}"
    typography: "{typography.label-caps}"
    rounded: "{rounded.full}"
    padding: "{spacing.md}"
  button-primary-hover:
    backgroundColor: "{colors.tertiary-strong}"
    textColor: "{colors.ink}"
  list-item-selected:
    backgroundColor: "{colors.tertiary-subtle}"
    textColor: "{colors.ink}"
    typography: "{typography.body-sm}"
    rounded: "{rounded.none}"
  card:
    backgroundColor: "{colors.surface-raised}"
    textColor: "{colors.on-surface}"
    typography: "{typography.body-md}"
    rounded: "{rounded.none}"
    padding: "{spacing.lg}"
  dialog:
    backgroundColor: "{colors.surface-overlay}"
    textColor: "{colors.on-surface}"
    typography: "{typography.body-md}"
    rounded: "{rounded.none}"
    padding: "{spacing.lg}"
  input:
    backgroundColor: "{colors.surface-raised}"
    textColor: "{colors.on-surface}"
    typography: "{typography.body-md}"
    rounded: "{rounded.none}"
    padding: "{spacing.sm}"
  input-error:
    backgroundColor: "{colors.surface-raised}"
    textColor: "{colors.on-surface}"
    typography: "{typography.body-md}"
    rounded: "{rounded.none}"
  alert-banner:
    backgroundColor: "{colors.error}"
    textColor: "{colors.on-surface}"
    typography: "{typography.label-caps}"
    rounded: "{rounded.none}"
    padding: "{spacing.sm}"
  badge-neutral:
    backgroundColor: "{colors.neutral}"
    textColor: "{colors.on-surface}"
    typography: "{typography.label-sm}"
    rounded: "{rounded.none}"
  divider:
    backgroundColor: "{colors.border}"
    height: 1px
  data-readout:
    backgroundColor: "{colors.surface-raised}"
    textColor: "{colors.secondary}"
    typography: "{typography.data-md}"
    rounded: "{rounded.none}"
    padding: "{spacing.xs}"
---

# The Con — LCARS Console

## Overview

This is a personal command console, not a productivity SaaS: one household's weather, calendar, tasks, sticky notes, and shortcut links, laid out as an LCARS operations display — the affectionate name for Michael Okuda's control-panel language from Star Trek: The Next Generation and Voyager. The system commits fully to that reference rather than gesturing at "sci-fi": a true-black viewport, wide colored structural panels with sharp corners and pill-shaped end caps, ALL-CAPS geometric labels, and a single vivid-cyan accent — the one hue in an otherwise all-blue palette that's reserved for things you can actually touch.

The read this should produce in the first second is **"this is a console, not a webpage."** That means giving up things a normal dashboard gets for free: no light mode (a powered LCARS panel has no light-mode state — it's either lit or it's off), no card-grid-with-shadows layout, no friendly rounded-everywhere softness, and no colorful decoration that doesn't also mean something. Every panel color in this system has exactly one job; the moment a color starts appearing "because it looks nice there," it has left the system.

Two structural rules carry more weight here than in most systems, because they are what makes an LCARS screen read as LCARS rather than as a dark-mode dashboard with big buttons:

1. **Panels are flush and separated by a black gap line, never by shadow or spacing.** Depth is conveyed by value (raised = lighter void), not by elevation.
2. **Color tells you what a thing is, not how important it is.** The blue-violet family (`primary`, `secondary*`) is structural — bars, elbows, end caps, section framing. It is never the answer to "what should the user click." That answer is always `tertiary`.

## Colors

The palette splits into two families with different jobs, plus a black-and-void axis that has no equivalent in a normal light/dark system.

**The structural family** — a single blue-violet hue bent from deep indigo to pale sky, the way LCARS panels have always been built from one family of blues and violets stacked at different values. Nothing here is decorative in the sense of "chosen because it looks nice" — each step marks a specific kind of structural element:

- **Primary (#1034B1):** deep indigo. The main command bar and the largest structural panels — the ones that frame a screen, like the "THE CON" title bar.
- **Primary Strong (#0E0967):** near-black cosmic violet. The darkest structural panels — corner elbows, end caps, anything that should read as the frame's outer edge.
- **Secondary (#67ABFF):** mid sky-blue. Repeating bar segments and secondary structural panels — the workhorse color that shows up most often in a given screen.
- **Secondary Subtle (#B1D9FF):** pale sky-blue. Corner elbows and the lightest structural accents, always paired with black text sitting directly on it.
- **Secondary Strong (#0056C5):** saturated signal blue, deepened from the app's original accent blue specifically so light text reads at AA contrast against it (the un-deepened value clears only 4.1:1). Used for the most attention-getting *structural* (not interactive) bar segments.

**The one interactive accent** — this system stays entirely within the blue family (no warm accent), so the interactive signal has to separate itself a different way: a hue-shift into vivid cyan, well clear of the indigo-to-sky-blue structural range, combined with a saturation and brightness nothing structural ever reaches. It reads as "the electrified one" next to the structural family's deeper, cooler blues and violets — a live circuit against inert panel plating:

- **Tertiary (#22D3EE), "Signal Cyan":** the sole driver of interaction. Every real button, every actionable Element, every "you can press this" surface uses this color and no other. No structural panel — `primary`, `primary-strong`, `secondary`, `secondary-subtle`, or `secondary-strong` — is ever this hue; that's what keeps it legible as a signal instead of just another panel color. Hold it under 5% of any screen — if more than one in twenty pixels is signal cyan, something that isn't actionable has been colored as if it were.
- **Tertiary Strong (#0891B2):** hover/active state for the accent — deeper and less saturated, like the circuit dimming under load.
- **Tertiary Subtle (#A5F3FC):** selection/highlight fill — a checked task row, a selected list item — never a button fill.

**Void and ink** — this system keeps true black in two distinct, deliberate roles, which is normally an anti-pattern this file would flag. Here it's the stated exception, because neither use is "the page background nobody chose":

- **Surface (#020207), "Void":** the viewport itself. Carries a whisper of violet chroma (not pure `#000`), matching the structural family's hue so the darkest and lightest ends of the system still belong to the same object.
- **Surface Raised (#060813) / Surface Overlay (#101321):** the dark-mode elevation ladder — cards, the to-do planner, sticky notes, and modals get progressively lighter as they come forward, since shadows do nothing against near-black.
- **Ink (#000000), pure black:** used only for text set directly on a colored panel and for the 2–4px gap line between adjacent panel segments. It is never a background on its own — it's mortar, not a surface.
- **Neutral (#52526A):** the one genuinely muted color, for disabled/inactive states and secondary metadata. Tinted cool violet rather than a true gray.
- **Border (#2B2C3D):** hairline dividers inside raised surfaces (list rows, input underlines) — distinct from Ink because a hairline inside a card is a much quieter gesture than the bold gap between two structural panels.
- **Error (#A50E19), "Red Alert":** deepened from the app's original alert red for the same reason as Secondary Strong — the lighter value fails AA for light text. Never rendered as red text on a dark field (that combination is close to unreadable here); always a filled `alert-banner`.

## Typography

Two families doing genuinely different jobs, plus a monospace for anything numeric — a three-face system justified by a recurring, real need in this product: weather temperatures, event times, and task due-dates all want tabular figures a text face can't give them.

- **Michroma** carries the console's *voice* — bar titles, section headers, and every button/label that sits directly on a colored panel, always set in caps. It's a geometric, single-weight display face built for exactly this register (it has no bold cut, which is a feature here: hierarchy in the display layer comes from size alone, never from faux-bold). Replaces the previous Instrument Sans, which was a stock shadcn-starter default with no connection to the console aesthetic. Fallback stack: `Michroma, 'Eurostile', 'Arial Narrow', sans-serif`.
- **IBM Plex Sans** carries the *apparatus* — actual prose: task notes, settings copy, dialog body text, form labels. Chosen for its engineered, slightly technical warmth (it was drawn for IBM's own systems documentation) and because it ships a real weight range without inviting a weight ladder — this system uses exactly two, 400 and 600. Fallback stack: `'IBM Plex Sans', system-ui, sans-serif`.
- **IBM Plex Mono** carries *data* — weather temperatures, event start times, due dates, any tabular readout. Always set with `tnum` (tabular figures) so columns of numbers don't jitter. Fallback stack: `'IBM Plex Mono', ui-monospace, monospace`.

All three are open-source (Google Fonts / IBM's OFL release) — self-host and subset to Latin; no commercial licensing to track even though the app ships as a native mobile build via NativePHP.

One deliberate exception to normal tracking rules: bar titles and panel labels (`display`, `headline-lg/md`, `label-caps`) are set in Michroma caps at **near-zero or slightly positive tracking**, not the wide tracking uppercase usually wants. Real LCARS lettering sits dense against the panel edge — it reads as engraved, not spaced-out. Save generous positive tracking (`label-sm`, +0.08em) for small IBM Plex Sans uppercase used in ordinary form/settings contexts, where the usual rule still applies.

## Layout

The console is edge-to-edge, not centered-with-max-width: bars and panels run the full viewport width, framed by a fixed grid of named areas (header / content / divider / footer bands) rather than a fluid content column. Panel segments sit **flush against each other — 0px gutter** — separated only by the Ink gap line described above; that flushness is structural, not an oversight, and should never be "fixed" by adding gap or padding between adjacent Bar/Elbow/BarEnd pieces.

Spacing runs on a 4px base (`xs` 4 → `2xl` 48), matching the Tailwind v4 scale already in use. Actual prose blocks — task notes, dialog copy, settings descriptions — still get a real measure (55–70ch) even though the chrome around them is edge-to-edge; console framing and readable text are different jobs and shouldn't share a rule.

The layout is asymmetric by construction: the left rail is a stack of small structural Elements (LCARS' signature "ladder" of colored blocks), the right/main area carries the actual widget content (weather, calendar, planner, services). Don't center this — asymmetry is the whole shape of an LCARS screen.

Mobile (NativePHP) builds respect safe-area insets (`--inset-left/right`) as padding on the outermost container, not as a layout change — the console frame doesn't reflow to a different structure on mobile, it insets.

## Elevation & Depth

Flat, deliberately — real LCARS has no drop shadows, blur, or glass. Depth is conveyed two ways only:

1. **The void ladder.** `surface` (Void) → `surface-raised` → `surface-overlay`, each step lighter, used for content that's conceptually "in front of" the console frame: cards, the planner, sticky notes, and modals. Never invert this — nothing gets *darker* to indicate elevation.
2. **The Ink gap line.** A 2–4px solid black rule between adjacent structural panel segments (already implemented via the `:after` pseudo-elements on end caps and the black top border on Elements). This is the system's only "seam," and it's doing the job shadows would do elsewhere.

No component in this system defines a shadow, blur, or glass treatment. If a future screen seems to need one for depth, the fix is almost always to move the content one step up the void ladder instead.

## Shapes

Radius is hierarchical and named for the LCARS panel vocabulary it belongs to, not generic `sm/md/lg`:

- **None (0px):** the default. Bars, structural panels, cards, dialogs, and inputs are sharp rectangles — an LCARS "girder" segment.
- **End (12px):** the rounded terminal cap on a bar segment (`BarEnd`) — one corner-pair rounded, matching the existing component's 0.75rem.
- **Elbow (24px):** the large pill corner on an `Elbow`/`Bracket` piece — the signature LCARS curve, matching the existing 1.5rem.
- **Full (9999px):** true pill shape, reserved for the primary button — the one fully-rounded shape in the system, which is exactly why it should stay rare. A pill shape *means* "press me" here; using it decoratively would blunt that signal.

Borders (`{colors.border}`) are used only as quiet hairlines inside a raised surface (list-row dividers, input underlines) — never as a substitute for the bold Ink gap line between structural panels, which is a different, louder gesture.

## Components

- **Buttons** (`button-primary` / `button-primary-hover`): the only components that use the signal-cyan family. Full-pill radius, Ink text, Michroma label-caps. There is no `button-secondary` — this system doesn't have a secondary button concept; an action either is the one thing you'd press (signal cyan) or it's a navigational Element (structural color, see below).
- **Elements / panel components** (`panel-structural`, `panel-accent`, `panel-decorative`, `panel-decorative-subtle`): the LCARS "ladder" blocks. Most render no text at all (they're pure framing); when one does carry a short label (e.g. "WEATHER", "TO DO"), use `label-caps` and the correct ink/on-surface text color for that panel's lightness. **Currently, several `:button="true"` Elements in the app (Add Service, Weather, To Do) use structural blue-family colors** — per this system they should move to `button-primary`/signal-cyan, or drop the button semantics and become plain navigation using `on-surface`-backed structural panels with a non-color affordance (underline, chevron) instead. Structural color must stop doubling as a click affordance.
- **Cards** (`card`): sticky notes, the to-do planner, and the weather widget. Sharp corners, `surface-raised`, generous `lg` padding — the one place in the system padding is generous rather than tight, because these are content surfaces, not frame.
- **Dialogs** (`dialog`): task modals and any overlay. `surface-overlay`, same sharp-corner rule — no modal in this system gets a shadow to lift it off the page; the value shift does that job.
- **Inputs** (`input` / `input-error`): sharp, `surface-raised`, `on-surface` text always — never colored text for error state. Pair an `alert-banner` above or below the field instead.
- **Alert banners** (`alert-banner`): a filled Red Alert strip — this is the correct home for validation errors, destructive-action confirmation, and any genuine warning. Red *text* is reserved for nothing; it doesn't pass contrast anywhere in this palette.
- **Data readouts** (`data-readout`): weather temperature, event start time, task due date — always IBM Plex Mono with tabular figures, always `secondary` text on a raised surface, so numeric information reads as "live telemetry" rather than ordinary body copy, without borrowing the interactive accent for something you can't press.
- **List selection** (`list-item-selected`): a checked task or a selected row gets the `tertiary-subtle` fill, not a border or checkmark-only change — visible from across the room, in keeping with a console meant to be read at a glance.

## Do's and Don'ts

- Do reserve `tertiary` (Signal Cyan) exclusively for things the user can actually activate. Don't use it on any static or decorative panel — if it stops meaning "press me," the whole accent stops working.
- Do keep structural panels flush with a black Ink gap line between them. Don't add spacing or shadow between adjacent Bar/Elbow/BarEnd pieces to "fix" the seam — the seam is the design.
- Do set every bar title and panel label in Michroma caps at tight/zero tracking. Don't fake it by upper-casing IBM Plex Sans with letter-spacing — that reads as a generic settings label, not a console control, and don't use Michroma below ~16px, where a display-only geometric face stops being legible.
- Do render error/validation states as a filled `alert-banner` (Red Alert). Don't render error text in red on a dark field — every red-on-void pairing in this palette fails AA contrast.
- Do move any Element or Button that's genuinely clickable onto the `tertiary` family. Don't leave interactive and purely-structural panels sharing the same indigo/sky-blue values `tertiary` is deliberately shifted away from — that's the one affordance gap in the current build this system is meant to close.
- Do use tabular figures (`tnum`) on every numeric readout. Don't let a weather temperature or event time render in a proportional face — the digits will jitter as they update.
- Do treat pure black (`ink`) as mortar between panels and pure `surface` void as the viewport background — two different jobs, two different tokens. Don't collapse them into one "black" value; the whisper of violet chroma in `surface` is what keeps the void from reading as a plain unmade `#000000`.
- Do stay dark-only. Don't build or wire up a light theme — LCARS has no lit/unlit ambiguity, and the Appearance settings' light/dark toggle should be removed rather than fixed.
- Do treat state changes as instant, like a physical panel toggling a light — a checked task, a hover, a selected row change in one frame. Don't add fade/ease transitions to panel color or opacity; the one exception is a genuine loading/live-data readout, where a brief (~150ms) crossfade on the number itself is acceptable. Don't add scroll-triggered animation anywhere — this is a console, not a landing page.
