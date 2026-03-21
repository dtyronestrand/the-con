import { defineConfigWithVueTs, vueTsConfigs } from '@vue/eslint-config-typescript';
import prettier from 'eslint-config-prettier';
import importPlugin from 'eslint-plugin-import';
import vue from 'eslint-plugin-vue';


export default defineConfigWithVueTs(
    vue.configs['flat/essential'],
    vueTsConfigs.recommended,
    {
        ignores: ['vendor', 'node_modules', 'public', 'bootstrap/ssr', 'tailwind.config.js', 'resources/js/components/ui/*'],
    },
    {
        plugins: {
            import: importPlugin,
       
        },
        settings: {
            'import/resolver': {
                typescript: {
                    alwaysTryTypes: true,
                    project: './tsconfig.json',
                },
            },
        },
        rules: {
            'vue/block-lang': ['error', {
                script: { "lang": "ts" },
                style: { allowNoLang: true },
                template: { allowNoLang: true }
            }],
            'vue/multi-word-component-names': 'off',
            '@typescript-eslint/no-explicit-any': 'off',
           
        },
    },
    prettier,
);