import { useEffect, useState } from 'react';
import { router } from '@inertiajs/react';
import { useKKiaPay } from 'kkiapay-react';

export default function PagePaiement({
    course,
    montant,
    referenceInterne,
    kkiapayPublicKey,
    kkiapaySandbox,
}) {
    const { openKkiapayWidget, addSuccessListener } = useKKiaPay();
    const [statutMessage, setStatutMessage] = useState('');
    const [enCours, setEnCours] = useState(false);

    useEffect(() => {
        addSuccessListener(({ transactionId }) => {
            setEnCours(true);
            setStatutMessage('Vérification du paiement en cours…');

            fetch(route('payment.store', course.id), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({
                    transaction_id: transactionId,
                    reference_interne: referenceInterne,
                }),
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.statut === 'succes' || data.statut === 'deja_confirme') {
                        setStatutMessage('Paiement confirmé ! Redirection…');
                        router.visit(route('paiement.retour'));
                    } else {
                        setEnCours(false);
                        setStatutMessage(
                            "Le paiement n'a pas pu être confirmé : " + (data.raison || '')
                        );
                    }
                })
                .catch(() => {
                    setEnCours(false);
                    setStatutMessage(
                        'Erreur réseau lors de la vérification. Contactez le support si le montant a été débité.'
                    );
                });
        });
    }, []);

    const handlePayer = () => {
        openKkiapayWidget({
            amount: montant,
            key: kkiapayPublicKey,
            sandbox: kkiapaySandbox,
            data: referenceInterne,
            reason: `Paiement du cours : ${course.title}`,
        });
    };

    return (
        <div style={{ maxWidth: 500, margin: '60px auto', textAlign: 'center', fontFamily: 'sans-serif' }}>
            <h1 style={{ fontSize: 22, marginBottom: 8 }}>Paiement du cours</h1>
            <p style={{ fontSize: 18, marginBottom: 4 }}>
                <strong>{course.title}</strong>
            </p>
            <p style={{ fontSize: 20, marginBottom: 24, color: '#2563eb' }}>
                {montant} FCFA
            </p>

            {kkiapaySandbox && (
                <div style={{
                    background: '#fef9c3', border: '1px solid #ca8a04',
                    borderRadius: 8, padding: '10px 16px', marginBottom: 20, fontSize: 14
                }}>
                    ⚠️ Mode TEST — aucun argent réel ne sera débité.
                </div>
            )}

            {statutMessage && (
                <div style={{
                    background: '#f0f9ff', border: '1px solid #0ea5e9',
                    borderRadius: 8, padding: '10px 16px', marginBottom: 20, fontSize: 14
                }}>
                    {statutMessage}
                </div>
            )}

            <button
                onClick={handlePayer}
                disabled={enCours}
                style={{
                    background: enCours ? '#94a3b8' : '#2563eb',
                    color: 'white', border: 'none', borderRadius: 8,
                    padding: '14px 32px', fontSize: 16, cursor: enCours ? 'not-allowed' : 'pointer',
                    width: '100%',
                }}
            >
                {enCours ? 'Traitement en cours…' : `Payer ${montant} FCFA`}
            </button>
        </div>
    );
}