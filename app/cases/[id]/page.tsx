"use client";

import { use, useState, useEffect, useRef } from "react";
import { useRouter } from "next/navigation";
import { ArrowLeft, Sparkles } from "lucide-react";
import Link from "next/link";

interface CasePageProps {
  params: Promise<{ id: string }>;
}

interface CaseItem {
  id: number;
  case_id: number;
  name: string;
  image_path: string;
  chance: number;
  value: number;
}

interface CaseData {
  id: number;
  name: string;
  description: string;
  price: number;
}

export default function CasePage({ params }: CasePageProps) {
  const router = useRouter();
  const { id } = use(params);

  const [isOpening, setIsOpening] = useState(false);
  const [wonItems, setWonItems] = useState<CaseItem[]>([]);
  const [showResult, setShowResult] = useState(false);
  const [fadeIn, setFadeIn] = useState(false);
  const [casesToOpen, setCasesToOpen] = useState(1);
  const [rouletteItems, setRouletteItems] = useState<CaseItem[][]>([]);
  const [selectedCase, setSelectedCase] = useState<CaseData | null>(null);
  const [caseItems, setCaseItems] = useState<CaseItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [balance, setBalance] = useState(0);

  const roulettesRef = useRef<HTMLDivElement | null>(null);
  const rowRefs = useRef<Array<HTMLDivElement | null>>([]);
  const itemWidthRef = useRef<number>(128);
  const gapRef = useRef<number>(8);
  const [transforms, setTransforms] = useState<number[]>([]);
  const [useTransition, setUseTransition] = useState<boolean>(false);

  const [isLogged, setIsLogged] = useState(false);

  useEffect(() => {
    const userStr = localStorage.getItem("user");
    if (!userStr) return setIsLogged(false);

    setIsLogged(true);
    const user = JSON.parse(userStr);

    fetch(`http://localhost/glimzyskins/backend/get-user-balance.php?user_id=${user.id}`)
      .then((res) => res.json())
      .then((data) => setBalance(Number(data.balance) || 0))
      .catch(() => setBalance(0));
  }, []);

  useEffect(() => {
    fetch(`http://localhost/glimzyskins/backend/get-case.php?id=${id}`)
      .then((res) => res.json())
      .then((data) => {
        if (!data?.case) return;
        setSelectedCase(data.case);

        const items = (data.items || []).map((i: any) => ({
          ...i,
          value: Number(i.value),
          chance: Number(i.chance),
        }));

        setCaseItems(items);
      })
      .finally(() => setLoading(false));
  }, [id]);

  useEffect(() => {
    if (!caseItems.length) return;

    const sets: CaseItem[][] = [];
    for (let c = 0; c < casesToOpen; c++) {
      const row = [];
      for (let i = 0; i < 50; i++)
        row.push(caseItems[Math.floor(Math.random() * caseItems.length)]);
      sets.push(row);
    }

    setRouletteItems(sets);
    setTransforms(new Array(sets.length).fill(0));
  }, [casesToOpen, caseItems]);

  const measureLayout = () => {
    const row = rowRefs.current[0];
    if (!row) return;

    const item = row.querySelector(".__case-item") as HTMLElement;
    if (item) {
      itemWidthRef.current = item.getBoundingClientRect().width;
      gapRef.current = 8;
    }
  };

  const getRarityColor = (value: number) => {
    if (value >= 10000) return "text-amber-500 bg-amber-500/10 border-amber-500/30";
    if (value >= 1000) return "text-purple-500 bg-purple-500/10 border-purple-500/30";
    if (value >= 200) return "text-blue-500 bg-blue-500/10 border-blue-500/30";
    return "text-neutral-400 bg-neutral-800 border-neutral-700";
  };

  const getRarityBgColor = (value: number) => {
    if (value >= 10000) return "bg-amber-500/20";
    if (value >= 1000) return "bg-purple-500/20";
    if (value >= 200) return "bg-blue-500/20";
    return "bg-neutral-800";
  };

  const openCase = async () => {
    if (isOpening || showResult) return;

    const userStr = localStorage.getItem("user");
    if (!userStr || !selectedCase) return router.push("/");

    const user = JSON.parse(userStr);
    const total = selectedCase.price * casesToOpen;

    if (balance < total) return alert("Niewystarczające saldo.");

    const res = await fetch("http://localhost/glimzyskins/backend/deduct-balance.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ user_id: user.id, amount: total }),
    });
    const data = await res.json();
    if (!data.success) return alert("Błąd płatności.");
    setBalance(Number(data.balance));

    setIsOpening(true);
    setShowResult(false);
    setWonItems([]);

    setTimeout(() => roulettesRef.current?.scrollIntoView({ behavior: "smooth" }), 200);

    const winners: CaseItem[] = [];
    const allRows: CaseItem[][] = [];

    for (let c = 0; c < casesToOpen; c++) {
      const row = [];
      for (let i = 0; i < 50; i++)
        row.push(caseItems[Math.floor(Math.random() * caseItems.length)]);

      const r = Math.random() * 100;
      let cum = 0;
      let winner = caseItems[0];

      for (const item of caseItems) {
        cum += item.chance;
        if (r <= cum) {
          winner = item;
          break;
        }
      }

      row[45] = winner;
      winners.push(winner);
      allRows.push(row);
    }

    setRouletteItems(allRows);
    setWonItems(winners);

    setTimeout(() => {
      measureLayout();
      const itemW = itemWidthRef.current;
      const gap = gapRef.current;
      const centerX = window.innerWidth / 2 - itemW / 2;

      const newT = allRows.map((_) => -(45 * (itemW + gap) - centerX));

      setTransforms(new Array(allRows.length).fill(centerX));

      requestAnimationFrame(() => {
        setUseTransition(true);
        setTransforms(newT);
      });

      setTimeout(async () => {
        setUseTransition(false);
        setIsOpening(false);

        await fetch("http://localhost/glimzyskins/backend/add-to-inventory.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            user_id: user.id,
            items: winners.map((w) => ({
              name: w.name,
              image_path: w.image_path,
              value: Number(w.value),
              case_name: selectedCase.name,
            })),
          }),
        });

        setTimeout(() => {
          setFadeIn(true);
          setShowResult(true);
        }, 400);
      }, 7200);
    }, 200);
  };

  if (loading)
    return (
      <div className="min-h-screen bg-neutral-900 flex items-center justify-center text-white">
        Ładowanie...
      </div>
    );

  if (!selectedCase)
    return (
      <div className="min-h-screen bg-neutral-900 flex items-center justify-center">
        <div className="text-center">
          <h1 className="text-3xl font-bold text-neutral-200 mb-4">Skrzynka nie znaleziona</h1>
          <Link href="/dashboardCases" className="text-amber-500 hover:text-amber-400">Wróć do listy skrzynek</Link>
        </div>
      </div>
    );

  const totalPrice = selectedCase.price * casesToOpen;
  const canOpen = balance >= totalPrice;

  return (
    <div className="min-h-screen bg-neutral-900 text-neutral-100">

      <nav className="w-full p-4 bg-neutral-950 border-b border-neutral-800">
        <div className="max-w-7xl mx-auto flex justify-between">
          <button onClick={() => router.back()} className="text-neutral-400 hover:text-neutral-200 flex items-center gap-2">
            <ArrowLeft className="w-5 h-5" /> Powrót
          </button>
          <div className="text-right">
            <div className="text-sm text-neutral-400">Saldo</div>
            <div className="text-amber-500 font-bold">{balance.toFixed(2)}zł</div>
          </div>
        </div>
      </nav>

      <div className="max-w-7xl mx-auto p-4">

        <div className="mb-4">
          <div className="flex justify-between mb-2">
            <div className="flex items-center gap-2">
              <div className="w-12 h-12 bg-neutral-950 rounded border border-neutral-700 flex items-center justify-center">
                <div className="w-8 h-8 bg-amber-500 rounded"></div>
              </div>
              <div>
                <h1 className="text-xl font-bold">{selectedCase.name}</h1>
                <p className="text-neutral-400 text-xs">{selectedCase.description}</p>
              </div>
            </div>

            <div className="text-right">
              <div className="text-sm text-neutral-400">Cena</div>
              <div className="text-amber-500 font-bold">{totalPrice.toFixed(2)}zł</div>
            </div>
          </div>

          <div className="flex gap-2 mb-2">
            {[1,2,3,4].map(n => (
              <button
                key={n}
                onClick={() => !isOpening && !showResult && setCasesToOpen(n)}
                disabled={isOpening || showResult}
                className={`flex-1 py-1.5 rounded-lg text-sm font-semibold ${
                  casesToOpen === n ? "bg-amber-600 text-white"
                    : "bg-neutral-800 text-neutral-300 hover:bg-neutral-700"
                }`}
              >
                {n}x
              </button>
            ))}
          </div>

          <button
            onClick={isOpening || showResult ? undefined : openCase}
            disabled={!canOpen || isOpening || showResult}
            className={`w-full py-2 px-4 rounded-lg font-semibold flex items-center justify-center gap-2 ${
              canOpen && !isOpening && !showResult
                ? "bg-amber-600 hover:bg-amber-700 text-white"
                : "bg-neutral-700 text-neutral-400"
            }`}
          >
            <Sparkles className="w-4 h-4" />
            {isOpening ? "Otwieranie..." : `Otwórz ${casesToOpen}x skrzynkę`}
          </button>
        </div>

        <div ref={roulettesRef} className="space-y-6 mb-6">
          {Array.from({ length: casesToOpen }).map((_, idx) => (
            <div key={idx} className="relative bg-neutral-800 rounded-lg p-3 border border-neutral-700 overflow-hidden">

              <div className="absolute inset-y-0 left-1/2 -translate-x-1/2 z-50 pointer-events-none">
                <div className="w-0.5 h-full bg-amber-500" />
              </div>

              <div
                ref={(el) => { rowRefs.current[idx] = el; }}
                className="flex gap-2"
                style={{
                  transform: `translateX(${transforms[idx]}px)`,
                  transition: useTransition
                    ? "transform 7s cubic-bezier(0.10, 0.85, 0.25, 1.00)"
                    : "none",
                }}
              >
                {rouletteItems[idx]?.map((item, i) => (
                  <div
                    key={i}
                    className={`__case-item shrink-0 w-32 h-36 ${getRarityBgColor(item.value)} rounded-lg border flex flex-col items-center justify-center p-3`}
                  >
                    <div className="w-20 h-20 bg-neutral-900 rounded mb-2 flex items-center justify-center overflow-hidden">
                      <img src={item.image_path} alt={item.name} className="w-full h-full object-contain" />
                    </div>
                    <p className="text-neutral-100 text-xs text-center">{item.name}</p>
                  </div>
                ))}
              </div>

            </div>
          ))}
        </div>

        {showResult && wonItems.length > 0 && (
          <div
            className={`
              fixed inset-0 
              bg-black/60 
              backdrop-blur-xl
              flex items-center justify-center z-50 p-4
              transition-all duration-500
              ${fadeIn ? "opacity-100" : "opacity-0"}
            `}
          >
            <div
              className={`
                bg-neutral-800 rounded-2xl p-6 max-w-md w-full border border-amber-500/40
                transition-all duration-500
                ${fadeIn ? "opacity-100 scale-100" : "opacity-0 scale-90"}
              `}
            >

              <h2 className="text-2xl font-bold text-center mb-6 text-neutral-100">
                Wygrane przedmioty
              </h2>

              <div className="flex flex-col gap-3 mb-6">
                {wonItems.map((item, idx) => (
                  <div key={idx} className={`p-4 rounded-lg border ${getRarityColor(item.value)}`}>
                    <div className="flex items-center gap-4">
                      <div className="w-16 h-16 bg-neutral-900 rounded overflow-hidden flex items-center justify-center">
                        <img src={item.image_path} alt={item.name} className="w-full h-full object-contain" />
                      </div>
                      <div className="flex-1">
                        <h3 className="text-neutral-200 font-semibold">{item.name}</h3>
                        <div className={`inline-block text-xs px-2 py-0.5 rounded ${getRarityColor(item.value)}`}>
                          {item.value} zł
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>

             <div className="flex flex-col gap-3">

  <button
    onClick={() => {
      setFadeIn(false);
      setTimeout(() => {
        setShowResult(false);
        router.push("/inventory");
      }, 300);
    }}
    className="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded font-semibold"
  >
    Przejdź do ekwipunku
  </button>

  <button
    onClick={() => {
      setFadeIn(false);
      setTimeout(() => {
        setShowResult(false);
      }, 300);
    }}
    className="w-full bg-neutral-700 hover:bg-neutral-600 text-neutral-200 py-2.5 rounded font-semibold"
  >
    Zamknij
  </button>

</div>
  </div>
          </div>
        )}

      </div>

<div className="max-w-6xl mx-auto bg-neutral-800 border border-neutral-700 rounded-xl p-6 my-10 shadow-xl">

  <h2 className="text-2xl font-bold mb-6 text-neutral-100 text-center">
    Dostępne przedmioty w skrzynce
  </h2>

  <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    {caseItems.map((item, idx) => (
      <div
        key={idx}
        className={`p-5 rounded-xl border ${getRarityColor(
          item.value
        )} bg-neutral-900/40 backdrop-blur-sm shadow-lg hover:scale-[1.02] transition-transform duration-200`}
      >
        <div className="w-full h-32 bg-neutral-900 rounded-lg flex items-center justify-center overflow-hidden mb-4">
          <img
            src={item.image_path}
            alt={item.name}
            className="w-full h-full object-contain p-2"
          />
        </div>

        <h3 className="text-neutral-100 text-lg font-semibold mb-1 text-center">
          {item.name}
        </h3>

        <div className="text-center text-sm">
          <p className="text-neutral-400">
            Szansa:{" "}
            <span className="text-neutral-100 font-semibold">{item.chance}%</span>
          </p>
          <p className="text-neutral-300">
            Wartość:{" "}
            <span className="font-semibold text-neutral-100">
              {item.value} zł
            </span>
          </p>
        </div>
      </div>
    ))}
  </div>

</div>
    </div>
  );
}