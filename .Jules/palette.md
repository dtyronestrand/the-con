## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2026-08-18 - Native Checkbox Keyboard Accessibility
**Learning:** Using `display: none` on native checkboxes inside custom labels breaks keyboard accessibility by removing the element from the tab order. Using `@blur` implicitly for saving form input (like `TaskInput`) requires explicitly binding `@keydown.enter` to the same blur function to maintain intuitive keyboard interaction.
**Action:** Always use visually hidden utility classes (e.g. Tailwind's `sr-only` and `peer`) instead of `display: none` for native inputs, and rely on `peer-focus-visible` to apply focus rings to custom sibling elements.
