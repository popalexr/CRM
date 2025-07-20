export interface GradientOption {
    name: string;
    class: string;
}

export const PROFILE_GRADIENTS: GradientOption[] = [
    { name: 'Ocean Blue', class: 'bg-gradient-to-br from-blue-400 via-purple-400 to-pink-400' },
    { name: 'Forest Green', class: 'bg-gradient-to-br from-green-400 via-emerald-400 to-teal-400' },
    { name: 'Sunset Orange', class: 'bg-gradient-to-br from-purple-400 via-pink-400 to-red-400' },
    { name: 'Golden Hour', class: 'bg-gradient-to-br from-orange-400 via-yellow-400 to-amber-400' },
    { name: 'Deep Space', class: 'bg-gradient-to-br from-gray-900 via-purple-900 to-violet-600' },
    { name: 'Mint Fresh', class: 'bg-gradient-to-br from-green-300 via-teal-300 to-cyan-400' },
    { name: 'Rose Garden', class: 'bg-gradient-to-br from-pink-300 via-rose-300 to-red-300' },
    { name: 'Arctic Blue', class: 'bg-gradient-to-br from-blue-200 via-indigo-300 to-purple-400' }
];
