## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2024-05-18 - Component Action Mapping Patterns
**Learning:** Found an app-specific pattern where nested interactive elements in `TaskModal.vue` and `Task.vue` were using implicit roles without keyboard listeners, dropping standard tab/focus flow. Custom Checkbox implementions were breaking a11y completely due to `display: none`.
**Action:** When working on custom form controls or task lists in this repository, always replace `display: none` with `.sr-only` equivalent techniques and bind explicit `@keydown` handlers on custom buttons to maintain standard a11y focus rings.
