## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2026-10-09 - Custom Checkbox Keyboard Accessibility
**Learning:** Using 'display: none' on a native input checkbox breaks keyboard focus. The element cannot receive focus, rendering any custom checkbox inaccessible to keyboard users.
**Action:** Use Tailwind's 'sr-only' class on the native input alongside the 'peer' class, and apply 'peer-focus-visible:ring-2' to a sibling element to maintain visual style while ensuring full keyboard accessibility.
