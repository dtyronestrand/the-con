## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2026-04-14 - Added proper aria-labels and focus states to modal buttons in ServiceContainer
**Learning:** Wrapping visual text symbols like '✕', '+', or '✎' inside a `<span aria-hidden="true">`, while adding an `aria-label` to the parent button, prevents screen readers from redundantly or confusingly reading both the symbol and the label.
**Action:** Use this pattern on all icon-only buttons built with literal text symbols.
