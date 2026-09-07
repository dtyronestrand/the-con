## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2026-09-07 - Added sr-only class instead of display: none for native inputs
**Learning:** The application uses native checkbox inputs with visual 'fakes'. Hiding them with `display: none` breaks keyboard accessibility entirely. Relying on `opacity-0 group-hover:opacity-100` for edit elements hides them from keyboard-only users.
**Action:** Always use Tailwind's `sr-only` (screen-reader only) for native form elements that are visually styled via siblings (`peer`). Always pair `group-hover:opacity-100` with `group-focus-within:opacity-100` for interactive container elements.
