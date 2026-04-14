export function translatePageLabel(label: string): string {
    return label
        .replace(/Previous/, 'Назад')
        .replace(/Next/, 'Вперёд');
}