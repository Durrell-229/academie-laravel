import { Link } from '@inertiajs/react';

export default function RetourPaiement() {
    return (
        <div style={{ maxWidth: 500, margin: '80px auto', textAlign: 'center', fontFamily: 'sans-serif' }}>
            <div style={{ fontSize: 60, marginBottom: 16 }}>✅</div>
            <h1 style={{ fontSize: 24, marginBottom: 12 }}>Paiement reçu !</h1>
            <p style={{ color: '#64748b', marginBottom: 24 }}>
                Votre paiement est en cours de traitement. L'accès au cours
                s'activera automatiquement dans quelques instants.
            </p>
            <Link
                href={route('dashboard')}
                style={{
                    background: '#2563eb', color: 'white', borderRadius: 8,
                    padding: '12px 28px', textDecoration: 'none', fontSize: 16,
                }}
            >
                Retour au tableau de bord
            </Link>
        </div>
    );
}