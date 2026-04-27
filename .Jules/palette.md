## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2026-04-27 - Symbol-Only Buttons Accessibility
**Learning:** Text symbols ('✎', '✕', '+') are used as icons in buttons but lack semantic span aria-hidden wrappers and aria-labels. Furthermore, buttons revealed only via group-hover:opacity-100 are inaccessible to keyboard users.
**Action:** Always wrap text symbol icons in span aria-hidden, provide descriptive aria-labels on their parent button, and ensure hover-revealed elements pair group-hover with group-focus-within and explicit focus-visible states.
